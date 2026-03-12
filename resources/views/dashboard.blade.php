@extends('layouts.' . config('settings.active_layout'))

@section('title', 'Cash reconciliation dashboard')

@section('content')
    <div class="row mb-4">
        <div class="col-md-8">
            <h4 class="mb-1">Cash reconciliation</h4>
            <p class="text-muted mb-0">
                Expected vs actual cash by shift — quickly spot mismatches and hold staff accountable.
            </p>
        </div>
        <div class="col-md-4">
            <form method="get" action="{{ route('dashboard') }}" class="row g-2 justify-content-md-end">
                <div class="col-6">
                    <label class="form-label mb-1">From</label>
                    <input type="date" name="from" value="{{ $from->format('Y-m-d') }}" class="form-control">
                </div>
                <div class="col-6">
                    <label class="form-label mb-1">To</label>
                    <div class="input-group">
                        <input type="date" name="to" value="{{ $to->format('Y-m-d') }}" class="form-control">
                        <button type="submit" class="btn btn-primary ms-1">
                            <i class="bx bx-filter-alt me-1"></i> Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-lg-3">
            <div class="card h-100">
                <div class="card-body">
                    <p class="mb-1 text-muted text-uppercase small">Total shifts</p>
                    <h3 class="mb-0">{{ $summary['total_shifts'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card h-100">
                <div class="card-body">
                    <p class="mb-1 text-muted text-uppercase small">Balanced</p>
                    <h3 class="mb-0 text-success">{{ $summary['balanced_count'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card h-100">
                <div class="card-body">
                    <p class="mb-1 text-muted text-uppercase small">Short (count / amount)</p>
                    <h3 class="mb-0 text-danger">
                        {{ $summary['short_count'] }}
                        <small class="text-muted">/ {{ number_format($summary['short_total_amount'], 2) }} PKR</small>
                    </h3>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card h-100">
                <div class="card-body">
                    <p class="mb-1 text-muted text-uppercase small">Over (count / amount)</p>
                    <h3 class="mb-0 text-warning">
                        {{ $summary['over_count'] }}
                        <small class="text-muted">/ {{ number_format($summary['over_total_amount'], 2) }} PKR</small>
                    </h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Recent reconciliations</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Branch</th>
                            <th>Cashier</th>
                            <th class="text-end">Expected</th>
                            <th class="text-end">Actual</th>
                            <th class="text-end">Difference</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($summary['reconciliations']->take(10) as $rec)
                            <tr>
                                <td>{{ $rec->created_at->format('M d, Y H:i') }}</td>
                                <td>{{ $rec->branch->name }}</td>
                                <td>{{ $rec->shift->cashier->name ?? '-' }}</td>
                                <td class="text-end">{{ number_format($rec->expected_amount, 2) }}</td>
                                <td class="text-end">{{ number_format($rec->actual_amount, 2) }}</td>
                                <td class="text-end
                                    {{ $rec->difference_type->value === 'short' ? 'text-danger' : ($rec->difference_type->value === 'over' ? 'text-warning' : '') }}">
                                    {{ number_format($rec->difference_amount, 2) }}
                                </td>
                                <td>{{ ucfirst($rec->status->value) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    No reconciliations in this period.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
