@extends('layouts.' . config('settings.active_layout'))
@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Add New Menu Item</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('menus.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="label" class="form-label">Menu Label *</label>
                            <input type="text" class="form-control @error('label') is-invalid @enderror"
                                id="label" name="label" value="{{ old('label') }}" required>
                            @error('label')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="url" class="form-label">URL *</label>
                            <input type="text" class="form-control @error('url') is-invalid @enderror"
                                id="url" name="url" value="{{ old('url') }}" placeholder="/page-url" required>
                            @error('url')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="parent_id" class="form-label">Parent Menu (Optional)</label>
                            <select class="form-select @error('parent_id') is-invalid @enderror"
                                id="parent_id" name="parent_id">
                                <option value="">-- No Parent (Top Level) --</option>
                                @foreach($parentMenus as $parentMenu)
                                <option value="{{ $parentMenu->id }}"
                                    {{ old('parent_id') == $parentMenu->id ? 'selected' : '' }}>
                                    {{ $parentMenu->label }}
                                </option>
                                @endforeach
                            </select>
                            @error('parent_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="target" class="form-label">Link Target *</label>
                            <select class="form-select @error('target') is-invalid @enderror"
                                id="target" name="target" required>
                                <option value="_self" {{ old('target') == '_self' ? 'selected' : '' }}>
                                    Same Window (_self)
                                </option>
                                <option value="_blank" {{ old('target') == '_blank' ? 'selected' : '' }}>
                                    New Window (_blank)
                                </option>
                            </select>
                            @error('target')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="css_class" class="form-label">CSS Class (Optional)</label>
                            <input type="text" class="form-control @error('css_class') is-invalid @enderror"
                                id="css_class" name="css_class" value="{{ old('css_class') }}"
                                placeholder="custom-class">
                            @error('css_class')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="status"
                                name="status" value="active" {{ old('status', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="status">
                                Active
                            </label>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('menus.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-success">Create Menu Item</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection