<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Settings\CompaniesController;
use App\Http\Controllers\Settings\CompanyTypesController;
use App\Http\Controllers\Settings\MenusController;
use App\Http\Controllers\Settings\MyPermissionsController;
use App\Http\Controllers\Settings\MyRolesController;
use App\Http\Controllers\Settings\SectionsController;
use App\Http\Controllers\Settings\SettingController;
use App\Http\Controllers\Settings\UserLogsController;
use App\Http\Controllers\Settings\UsersController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/clear', function () {
    Artisan::call('optimize:clear');
    Artisan::call('cache:clear');

    return response('All caches cleared.');
});

Route::get('/link', function () {
    Artisan::call('storage:link');

    return response('Storage link created.');
});

Route::redirect('/', 'login');

Route::get('/home/screen', [HomeController::class, 'index'])->name('app.landing-screen');
Route::get('/home/screen/search', [HomeController::class, 'search'])->name('app.landing-screen.search');

Auth::routes();

Route::post('custom-authenticate', [\App\Http\Controllers\Auth\LoginController::class, 'customAuthenticate'])->name('custom-authenticate');

Route::prefix('forget-password')->name('password.')->group(function (): void {
    Route::get('/', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'showForm'])->name('forgot.view');
    Route::post('send-otp', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendOtpWeb'])->name('send.otp');
    Route::post('verify-otp', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'verifyOtp'])->name('verify.otp');
    Route::post('reset', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'resetPassword'])->name('reset');
});

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::post('logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/');
})->name('logout')->middleware('auth');

Route::middleware(['auth', 'business'])->group(function (): void {
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('branches', \App\Http\Controllers\BranchController::class);
    Route::get('shifts/create', [\App\Http\Controllers\ShiftController::class, 'create'])->name('shifts.create');
    Route::get('shifts', [\App\Http\Controllers\ShiftController::class, 'index'])->name('shifts.index');
    Route::post('shifts', [\App\Http\Controllers\ShiftController::class, 'store'])->name('shifts.store');
    Route::get('shifts/{shift}', [\App\Http\Controllers\ShiftController::class, 'show'])->name('shifts.show');
    Route::post('shifts/{shift}/close', [\App\Http\Controllers\ShiftController::class, 'close'])->name('shifts.close');
    Route::post('shifts/{shift}/cash-count', [\App\Http\Controllers\CashCountController::class, 'store'])->name('shifts.cash-count.store');
    Route::post('shifts/{shift}/cash-count/lock', [\App\Http\Controllers\CashCountController::class, 'lock'])->name('shifts.cash-count.lock');
    Route::post('shifts/{shift}/pos', [\App\Http\Controllers\PosSalesRecordController::class, 'store'])->name('shifts.pos.store');
    Route::post('shifts/{shift}/pos/lock', [\App\Http\Controllers\PosSalesRecordController::class, 'lock'])->name('shifts.pos.lock');
    Route::get('reconciliations', [\App\Http\Controllers\ReconciliationController::class, 'index'])->name('reconciliations.index');
    Route::get('reconciliations/{reconciliation}', [\App\Http\Controllers\ReconciliationController::class, 'show'])->name('reconciliations.show');
    Route::post('shifts/{shift}/reconcile', [\App\Http\Controllers\ReconciliationController::class, 'store'])->name('shifts.reconcile');
    Route::put('reconciliations/{reconciliation}/status', [\App\Http\Controllers\ReconciliationController::class, 'updateStatus'])->name('reconciliations.update-status');
});

Route::middleware(['auth'])->group(function (): void {
    Route::get('business/create', [\App\Http\Controllers\BusinessController::class, 'create'])->name('business.create');
    Route::post('business', [\App\Http\Controllers\BusinessController::class, 'store'])->name('business.store');
    Route::get('business/{business}', [\App\Http\Controllers\BusinessController::class, 'show'])->name('business.show');
});

Route::middleware(['auth'])->group(function () {
    Route::prefix('settings')->group(function () {
        Route::get('/', [SettingController::class, 'settings'])->name('settings.home')->middleware('permission:settings.settings.edit');

        Route::prefix('menus')->group(function () {
            Route::controller(MenusController::class)->group(function () {
                Route::get('/', 'index')->name('settings.menus.list');
                Route::get('create', 'create')->name('settings.menus.create');
                Route::post('store', 'store')->name('settings.menus.store');
                Route::get('edit/{id}', 'edit')->name('settings.menus.edit');
                Route::put('update/{id}', 'update')->name('settings.menus.update');
                Route::delete('delete/{id}', 'destroy')->name('settings.menus.delete');
                Route::post('menus-by-app-id', 'menuByAppId')->name('settings.menus.menus-by-app-id');
            });
        });

        Route::prefix('my-permissions')->group(function () {
            Route::controller(MyPermissionsController::class)->group(function () {
                Route::get('/', 'index')->name('settings.my-permissions.list');
                Route::get('create', 'create')->name('settings.my-permissions.create');
                Route::post('store', 'store')->name('settings.my-permissions.store');
                Route::get('edit/{id}', 'edit')->name('settings.my-permissions.edit');
                Route::put('update/{id}', 'update')->name('settings.my-permissions.update');
                Route::delete('delete/{id}', 'destroy')->name('settings.my-permissions.delete');
            });
        });

        Route::prefix('my-roles')->group(function () {
            Route::controller(MyRolesController::class)->group(function () {
                Route::get('/', 'index')->name('settings.my-roles.list');
                Route::get('role-permissions/{id}', 'show')->name('settings.my-roles.show');
                Route::post('role-permissions-save/{id}', 'rolePermissionsSave')->name('settings.my-roles.role-permissions-save');
                Route::get('create', 'create')->name('settings.my-roles.create');
                Route::post('store', 'store')->name('settings.my-roles.store');
                Route::get('edit/{id}', 'edit')->name('settings.my-roles.edit');
                Route::put('update/{id}', 'update')->name('settings.my-roles.update');
                Route::delete('delete/{id}', 'destroy')->name('settings.my-roles.delete');
            });
        });

        Route::prefix('companies')->group(function () {
            Route::controller(CompaniesController::class)->group(function () {
                Route::get('/', 'index')->name('settings.companies.list');
                Route::get('create', 'create')->name('settings.companies.create');
                Route::post('store', 'store')->name('settings.companies.store');
                Route::get('edit/{id}', 'edit')->name('settings.companies.edit');
                Route::put('update/{id}', 'update')->name('settings.companies.update');
                Route::delete('delete/{id}', 'destroy')->name('settings.companies.delete');
                Route::post('get-company-details', 'company_details')->name('settings.companies.details');
                Route::post('check-domain-prefix', 'checkDomainPrefix')->name('settings.companies.check-domain-prefix');
            });
        });

        Route::prefix('company-types')->group(function () {
            Route::controller(CompanyTypesController::class)->group(function () {
                Route::get('/', 'index')->name('settings.company-types.list');
                Route::get('create', 'create')->name('settings.company-types.create');
                Route::post('store', 'store')->name('settings.company-types.store');
                Route::get('edit/{id}', 'edit')->name('settings.company-types.edit');
                Route::put('update/{id}', 'update')->name('settings.company-types.update');
                Route::delete('delete/{id}', 'destroy')->name('settings.company-types.delete');
            });
        });

        Route::prefix('sections')->name('settings.sections.')->group(function () {
            Route::controller(SectionsController::class)->group(function () {
                Route::get('/', 'index')->name('list');
                Route::get('datatable', 'datatable')->name('datatable');
                Route::get('create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
                Route::get('edit/{id}', 'edit')->name('edit');
                Route::put('update/{id}', 'update')->name('update');
                Route::delete('delete/{id}', 'destroy')->name('delete');
            });
        });

        Route::prefix('user-logs')->group(function () {
            Route::controller(UserLogsController::class)->group(function () {
                Route::get('/', 'allLogs')->name('settings.user_logs.index');
                Route::get('list', 'logsList')->name('settings.user_logs.list');
                Route::get('export/{format}', 'export')->name('settings.user_logs.export');
                Route::get('all', 'allLogs')->name('settings.user_logs.all');
            });
        });

        Route::prefix('settings')->group(function () {
            Route::controller(SettingController::class)->group(function () {
                Route::get('/', 'settings')->name('settings.settings')->middleware('permission:settings.settings.edit');
                Route::post('save', 'save')->name('settings.save');
            });
        });

        Route::prefix('users-mgt')->group(function () {
            Route::controller(UsersController::class)->group(function () {
                Route::get('/', 'index')->name('settings.users-mgt.list')->middleware('permission:users.mgt.list');
                Route::get('users-list-dt', 'usersList')->name('settings.users-mgt.users-list-dt');
                Route::prefix('user-permissions')->group(function () {
                    Route::get('/{id}', 'show')->name('settings.users-mgt.show');
                    Route::post('/{id}', 'userPermissionsSave')->name('settings.users-mgt.user-permissions-save');
                })->middleware('permission:users.mgt.assign.permissions');
                Route::get('create', 'create')->name('settings.users-mgt.create')->middleware('permission:users.mgt.create');
                Route::post('store', 'store')->name('settings.users-mgt.store')->middleware('permission:users.mgt.create');
                Route::get('edit/{id}', 'edit')->name('settings.users-mgt.edit')->middleware('permission:users.mgt.edit');
                Route::put('update/{id}', 'update')->name('settings.users-mgt.update')->middleware('permission:users.mgt.edit');
                Route::delete('delete/{id}', 'destroy')->name('settings.users-mgt.delete')->middleware('permission:users.mgt.delete');
                Route::post('restore/{id}', 'restore')->name('settings.users-mgt.restore');
                Route::post('toggle-status/{id}', 'toggleStatus')->name('settings.users-mgt.toggle-status')->middleware('permission:users.mgt.edit');
                Route::get('my-profile', 'myProfile')->name('settings.users-mgt.my-profile');
                Route::post('my-profile-save', 'myProfileAct')->name('settings.users-mgt.my-profile-save');
                Route::get('change-password', 'changePassword')->name('settings.users-mgt.change-password');
                Route::post('change-password-save', 'changePasswordAct')->name('settings.users-mgt.change-password-save');
                Route::get('config-pincode', 'configPincode')->name('settings.users-mgt.config-pincode');
                Route::post('config-pincode-save', 'configPincodeAct')->name('settings.users-mgt.config-pincode-save');
                Route::get('import', 'import')->name('settings.users-mgt.import');
                Route::post('import', 'importUsers')->name('settings.users-mgt.import.importUsers');
            });
        });
    });
});
