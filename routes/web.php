<?php

use App\Http\Controllers\BranchController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\CashCountController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PosSalesRecordController;
use App\Http\Controllers\ReconciliationController;
use App\Http\Controllers\ShiftController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/');
})->name('logout')->middleware('auth');

Route::middleware(['auth', 'business'])->group(function (): void {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('branches', BranchController::class);

    Route::get('shifts/create', [ShiftController::class, 'create'])->name('shifts.create');
    Route::get('shifts', [ShiftController::class, 'index'])->name('shifts.index');
    Route::post('shifts', [ShiftController::class, 'store'])->name('shifts.store');
    Route::get('shifts/{shift}', [ShiftController::class, 'show'])->name('shifts.show');
    Route::post('shifts/{shift}/close', [ShiftController::class, 'close'])->name('shifts.close');

    Route::post('shifts/{shift}/pos', [PosSalesRecordController::class, 'store'])->name('shifts.pos.store');
    Route::post('shifts/{shift}/pos/lock', [PosSalesRecordController::class, 'lock'])->name('shifts.pos.lock');

    Route::post('shifts/{shift}/cash-count', [CashCountController::class, 'store'])->name('shifts.cash-count.store');
    Route::post('shifts/{shift}/cash-count/lock', [CashCountController::class, 'lock'])->name('shifts.cash-count.lock');

    Route::get('reconciliations', [ReconciliationController::class, 'index'])->name('reconciliations.index');
    Route::get('reconciliations/{reconciliation}', [ReconciliationController::class, 'show'])->name('reconciliations.show');
    Route::post('shifts/{shift}/reconcile', [ReconciliationController::class, 'store'])->name('shifts.reconcile');
    Route::put('reconciliations/{reconciliation}/status', [ReconciliationController::class, 'updateStatus'])->name('reconciliations.update-status');
});

Route::middleware(['auth'])->group(function (): void {
    Route::get('business/create', [BusinessController::class, 'create'])->name('business.create');
    Route::post('business', [BusinessController::class, 'store'])->name('business.store');
    Route::get('business/{business}', [BusinessController::class, 'show'])->name('business.show');
});
