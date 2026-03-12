@extends('layouts.' . config('settings.active_layout'))

@section('title', 'Branches')

@section('content')
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Branches</h4>
            <a href="{{ route('branches.create') }}" class="btn btn-primary">
                <i class="bx bx-plus me-1"></i> Add branch
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Code</th>
                            <th class="text-end">Shifts</th>
                            <th class="text-center" style="width: 120px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($branches as $branch)
                            <tr>
                                <td class="fw-medium">{{ $branch->name }}</td>
                                <td class="text-muted">{{ $branch->code ?? '—' }}</td>
                                <td class="text-end">{{ $branch->shifts_count }}</td>
                                <td class="text-center">
                                    <a href="{{ route('branches.show', $branch) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bx bx-show"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    No branches yet. Create your first branch to start tracking shifts.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
