@extends('layouts.'.config('settings.active_layout'))

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Create Model Filter</h4>
            </div>
            <div class="card-body">
                
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('settings.model.filters.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <!-- Filter For Section -->
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">Filter Assignment</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="filter_for_type" class="form-label">Filter For Type</label>
                                        <select class="form-select" id="filter_for_type" name="filter_for_type" required>
                                            <option value="">Select Type</option>
                                            <option value="App\Models\User" {{ old('filter_for_type') == 'App\Models\User' ? 'selected' : '' }}>User</option>
                                            <option value="App\Models\MyRole" {{ old('filter_for_type') == 'App\Models\MyRole' ? 'selected' : '' }}>Role</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="filter_for_id" class="form-label">Filter For Entity</label>
                                        <select class="form-select" id="filter_for_id" name="filter_for_id" required>
                                            <option value="">Select Entity</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Model Section -->
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">Target Model</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="model_type" class="form-label">Model Type</label>
                                        <select class="form-select" id="model_type" name="model_type" required>
                                            <option value="">Select Model</option>
                                            @foreach($availableModels as $modelClass => $modelName)
                                                <option value="{{ $modelClass }}" {{ old('model_type') == $modelClass ? 'selected' : '' }}>
                                                    {{ $modelName }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="model_id" class="form-label">Specific Instance (Optional)</label>
                                        <input type="number" class="form-control" id="model_id" name="model_id" 
                                               value="{{ old('model_id') }}" placeholder="Leave empty for all instances">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filter Options -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h6 class="card-title mb-0">Filter Options</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="is_filter_by_user" 
                                               name="is_filter_by_user" value="1" {{ old('is_filter_by_user') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_filter_by_user">
                                            Filter by User
                                        </label>
                                        <small class="form-text text-muted d-block">Filter records by the logged-in user</small>
                                    </div>

                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="is_filter_by_company" 
                                               name="is_filter_by_company" value="1" {{ old('is_filter_by_company') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_filter_by_company">
                                            Filter by Company
                                        </label>
                                        <small class="form-text text-muted d-block">Filter records by user's company</small>
                                    </div>

                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="is_filter_by_section" 
                                               name="is_filter_by_section" value="1" {{ old('is_filter_by_section') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_filter_by_section">
                                            Filter by Section
                                        </label>
                                        <small class="form-text text-muted d-block">Filter records by user's section</small>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="is_filter_by_unit" 
                                               name="is_filter_by_unit" value="1" {{ old('is_filter_by_unit') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_filter_by_unit">
                                            Filter by Unit
                                        </label>
                                        <small class="form-text text-muted d-block">Filter records by user's unit</small>
                                    </div>

                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="is_filter_by_status" 
                                               name="is_filter_by_status" value="1" {{ old('is_filter_by_status') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_filter_by_status">
                                            Filter by Status
                                        </label>
                                        <small class="form-text text-muted d-block">Only show active records</small>
                                    </div>

                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="is_active" 
                                               name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Active Filter
                                        </label>
                                        <small class="form-text text-muted d-block">Enable this filter</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-check"></i> Create Filter
                            </button>
                            <a href="{{ route('settings.model.filters.index') }}" class="btn btn-secondary">
                                <i class="bx bx-x"></i> Cancel
                            </a>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Handle filter type change
    $('#filter_for_type').change(function() {
        const type = $(this).val();
        const entitySelect = $('#filter_for_id');
        
        entitySelect.html('<option value="">Loading...</option>');
        
        if (type) {
            $.ajax({
                url: '{{ route("settings.model.filters.get-entities") }}',
                method: 'GET',
                data: { type: type },
                success: function(data) {
                    entitySelect.html('<option value="">Select Entity</option>');
                    data.forEach(function(entity) {
                        entitySelect.append(`<option value="${entity.id}">${entity.title}</option>`);
                    });
                },
                error: function() {
                    entitySelect.html('<option value="">Error loading entities</option>');
                }
            });
        } else {
            entitySelect.html('<option value="">Select Entity</option>');
        }
    });
});
</script>
@endpush