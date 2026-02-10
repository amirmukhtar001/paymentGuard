@extends('layouts.' . config('settings.active_layout'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">

            <div class="card-header header-elements-inline">
                <h5 class="card-title">{{ $title }}</h5>
                <div class="header-elements"></div>
            </div>

            <div class="card-body">
                <form method="POST"
                    action="{{ $item->exists
                            ? route('settings.stat-definitions.update', $item->uuid)
                            : route('settings.stat-definitions.store') }}">
                    @csrf
                    @if($item->exists)
                    @method('PUT')
                    @endif

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label req">Key</label>
                                <span class="help">
                                    @if($errors->has('key')) {!! $errors->first('key') !!} @endif
                                </span>
                                <input type="text" name="key" class="form-control"
                                    value="{{ old('key', $item->key ?? '') }}"
                                    placeholder="population, literacy_rate" required>
                                <small class="text-muted">Use lowercase + underscore only</small>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">GeoJSON Key</label>
                                <span class="help">
                                    @if($errors->has('geojson_key')) {!! $errors->first('geojson_key') !!} @endif
                                </span>
                                <input type="text"
                                    name="geojson_key"
                                    class="form-control"
                                    value="{{ old('geojson_key', $item->geojson_key ?? '') }}"
                                    placeholder="district_population">
                                <small class="text-muted">
                                    Optional. If set, this stat will be exported to GeoJSON.
                                </small>
                            </div>
                        </div>


                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label req">Label</label>
                                <span class="help">
                                    @if($errors->has('label')) {!! $errors->first('label') !!} @endif
                                </span>
                                <input type="text" name="label" class="form-control"
                                    value="{{ old('label', $item->label ?? '') }}" required>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="form-label req">Type</label>
                                <span class="help">
                                    @if($errors->has('type')) {!! $errors->first('type') !!} @endif
                                </span>
                                @php $typeVal = old('type', $item->type ?? 'integer'); @endphp
                                <select name="type" class="form-control" required>
                                    <option value="integer" {{ $typeVal === 'integer' ? 'selected' : '' }}>Integer</option>
                                    <option value="decimal" {{ $typeVal === 'decimal' ? 'selected' : '' }}>Decimal</option>
                                    <option value="percent" {{ $typeVal === 'percent' ? 'selected' : '' }}>Percent</option>
                                    <option value="text" {{ $typeVal === 'text' ? 'selected' : '' }}>Text</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">Unit</label>
                                <span class="help">
                                    @if($errors->has('unit')) {!! $errors->first('unit') !!} @endif
                                </span>
                                <input type="text" name="unit" class="form-control"
                                    value="{{ old('unit', $item->unit ?? '') }}"
                                    placeholder="people, km², %">
                            </div>
                        </div>
                    </div>

                    <div class="row mt-1">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="form-label">Required</label>
                                @php $reqVal = (int) old('is_required', (int)($item->is_required ?? 0)); @endphp
                                <select name="is_required" class="form-control">
                                    <option value="0" {{ $reqVal === 0 ? 'selected' : '' }}>No</option>
                                    <option value="1" {{ $reqVal === 1 ? 'selected' : '' }}>Yes</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="form-label">Active</label>
                                @php $activeVal = (int) old('is_active', (int)($item->is_active ?? 1)); @endphp
                                <select name="is_active" class="form-control">
                                    <option value="1" {{ $activeVal === 1 ? 'selected' : '' }}>Yes</option>
                                    <option value="0" {{ $activeVal === 0 ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="form-label">Sort Order</label>
                                <input type="number" name="sort_order" class="form-control"
                                    value="{{ old('sort_order', $item->sort_order ?? 0) }}" min="0">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">Min</label>
                                <input type="number" name="min" class="form-control"
                                    value="{{ old('min', $item->min ?? '') }}" step="any">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">Max</label>
                                <input type="number" name="max" class="form-control"
                                    value="{{ old('max', $item->max ?? '') }}" step="any">
                            </div>
                        </div>
                    </div>

                    {{-- Buttons --}}
                    <div class="row mt-3">
                        <div class="col-12">
                            <a href="{{ route('settings.stat-definitions.list') }}" class="btn btn-warning">
                                <i class="bx bx-arrow-back tf-icons"></i> Back
                            </a>

                            <button type="submit" class="btn btn-success">
                                <i class="bx bx-save"></i> Save
                            </button>
                        </div>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>
@endsection