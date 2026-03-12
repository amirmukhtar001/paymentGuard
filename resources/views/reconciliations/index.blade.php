@extends('layouts.' . config('settings.active_layout'))

@section('title', 'Reconciliations')

@section('content')
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Reconciliations</h4>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Branch</th>
                            <th>Cashier</th>
                            <th class="text-end">Difference</th>
                            <th>Status</th>
                            <th class="text-center" style="width: 120px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reconciliations as $rec)
                            <tr>
                                <td>{{ $rec->created_at->format('M d, Y') }}</td>
                                <td>{{ $rec->branch->name }}</td>
                                <td>{{ $rec->shift->cashier->name ?? '-' }}</td>
                                <td class="text-end
                                    {{ $rec->difference_type->value === 'short' ? 'text-danger' : '' }}">
                                    {{ number_format($rec->difference_amount, 2) }}
                                </td>
                                <td>{{ ucfirst($rec->status->value) }}</td>
                                <td class="text-center">
                                    <a href="{{ route('reconciliations.show', $rec) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bx bx-show"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    No reconciliations yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {{ $reconciliations->links() }}
            </div>
        </div>
    </div>
@endsection
