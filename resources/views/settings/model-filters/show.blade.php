@extends('layouts.'.config('settings.active_layout'))

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Model Filter Details</h4>
            </div>
            <div class="card-body">
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Filter Assignment</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Filter For Type</label>
                                    <p class="form-control-static">{{ class_basename($filter->filter_for_type) }}</p>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Filter For Entity</label>
                                    <p class="form-control-static">
                                        @if($filter->filterFor)
                                            {{ $filter->filterFor->name ?? $filter->filterFor->title ?? 'ID: ' . $filter->filter_for_id }}
                                        @else
                                            ID: {{ $filter->filter_for_id }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Target Model</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Model Type</label>
                                    <p class="form-control-static">{{ class_basename($filter->model_type) }}</p>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Specific Instance</label>
                                    <p class="form-control-static">
                                        @if($filter->model_id)
                                            ID: {{ $filter->model_id }}
                                            @if($filter->model)
                                                - {{ $filter->model->name ?? $filter->model->title ?? '' }}
                                            @endif
                                        @else
                                            All instances
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filter Options -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Filter Configuration</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Filter by User</label>
                                    <p class="form-control-static">
                                        @if($filter->is_filter_by_user)
                                            <span class="badge bg-success">Enabled</span>
                                        @else
                                            <span class="badge bg-secondary">Disabled</span>
                                        @endif
                                    </p>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Filter by Company</label>
                                    <p class="form-control-static">
                                        @if($filter->is_filter_by_company)
                                            <span class="badge bg-success">Enabled</span>
                                        @else
                                            <span class="badge bg-secondary">Disabled</span>
                                        @endif
                                    </p>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Filter by Section</label>
                                    <p class="form-control-static">
                                        @if($filter->is_filter_by_section)
                                            <span class="badge bg-success">Enabled</span>
                                        @else
                                            <span class="badge bg-secondary">Disabled</span>
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Filter by Unit</label>
                                    <p class="form-control-static">
                                        @if($filter->is_filter_by_unit)
                                            <span class="badge bg-success">Enabled</span>
                                        @else
                                            <span class="badge bg-secondary">Disabled</span>
                                        @endif
                                    </p>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Filter by Status</label>
                                    <p class="form-control-static">
                                        @if($filter->is_filter_by_status)
                                            <span class="badge bg-success">Enabled</span>
                                        @else
                                            <span class="badge bg-secondary">Disabled</span>
                                        @endif
                                    </p>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Filter Status</label>
                                    <p class="form-control-static">
                                        @if($filter->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Metadata -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Metadata</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Created At</label>
                                    <p class="form-control-static">{{ $filter->created_at->format('M d, Y H:i:s') }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Updated At</label>
                                    <p class="form-control-static">{{ $filter->updated_at->format('M d, Y H:i:s') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        @can('settings.model.filters.edit')
                            <a href="{{ route('settings.model.filters.edit', $filter->id) }}" class="btn btn-warning">
                                <i class="bx bx-edit"></i> Edit
                            </a>
                        @endcan
                        @can('settings.model.filters.view')
                            <a href="{{ route('settings.model.filters.index') }}" class="btn btn-secondary">
                                <i class="bx bx-arrow-back"></i> Back to List
                            </a>
                        @endcan
                        @can('settings.model.filters.delete')
                            <form action="{{ route('settings.model.filters.destroy', $filter->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" 
                                        onclick="return confirm('Are you sure you want to delete this filter?')">
                                    <i class="bx bx-trash"></i> Delete
                                </button>
                            </form>
                        @endcan
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection