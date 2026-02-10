@extends('layouts.' . config('settings.active_layout'))

@push('scripts')
    <script src="{{ asset('assets/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $(".select2").select2();
        });
    </script>
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        {{ isset($diagnosis) ? 'Edit Diagnosis' : 'Create New Diagnosis' }}
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ isset($diagnosis) ? route('settings.diagnoses.update', $diagnosis->id) : route('settings.diagnoses.store') }}">
                        @csrf
                        @if(isset($diagnosis))
                            @method('PUT')
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="section_id" class="form-label">Section <span class="text-danger">*</span></label>
                                    <select name="section_id" id="section_id" class="form-control select2 @error('section_id') is-invalid @enderror" required>
                                        <option value="">Select Section</option>
                                        @foreach($sections as $section)
                                            <option value="{{ $section->id }}" {{ (old('section_id', $diagnosis->section_id ?? '') == $section->id) ? 'selected' : '' }}>
                                                {{ $section->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('section_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="configuration_id" class="form-label">Level <span class="text-danger">*</span></label>
                                    <select name="configuration_id" id="configuration_id" class="form-control select2 @error('configuration_id') is-invalid @enderror" required>
                                        <option value="">Select Level</option>
                                        @foreach($levels as $level)
                                            <option value="{{ $level->id }}" {{ (old('configuration_id', $diagnosis->configuration_id ?? '') == $level->id) ? 'selected' : '' }}>
                                                {{ $level->value }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('configuration_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group mb-3">
                                    <label for="name" class="form-label">Diagnosis Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                                           value="{{ old('name', $diagnosis->name ?? '') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="icd_code" class="form-label">ICD Code</label>
                                    <input type="text" name="icd_code" id="icd_code" class="form-control @error('icd_code') is-invalid @enderror" 
                                           value="{{ old('icd_code', $diagnosis->icd_code ?? '') }}" placeholder="e.g. A00.1">
                                    @error('icd_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea name="description" id="description" rows="4" class="form-control @error('description') is-invalid @enderror" 
                                              placeholder="Enter diagnosis description...">{{ old('description', $diagnosis->description ?? '') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" name="is_active" id="is_active" class="form-check-input" 
                                               value="1" {{ old('is_active', $diagnosis->is_active ?? true) ? 'checked' : '' }}>
                                        <label for="is_active" class="form-check-label">Active</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="mdi mdi-content-save"></i> {{ isset($diagnosis) ? 'Update' : 'Save' }}
                            </button>
                            <a href="{{ route('settings.diagnoses.index') }}" class="btn btn-secondary">
                                <i class="mdi mdi-arrow-left"></i> Back
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection