@extends('layouts.' . config('settings.active_layout'))

@push('scripts')
<script>
{{-- Add Department-specific JS here if ever needed --}}
$(document).ready(function() {
// Event listener for the 'name' field input
$('#name').on('input', function() {
var nameValue = $(this).val().trim(); // Get the value of the name field
// Split the name into words
var words = nameValue.split(' ');
// Get the first letter of each word and store it in an array
var shortCode = words
.map(function(word, index) {
// Capitalize the first letter of each word
return word.charAt(0).toUpperCase();
});
// If there is more than one letter, add '&' before the last letter
if (shortCode.length > 1) {
var lastLetter = shortCode.pop(); // Remove the last letter
shortCode.push('&' + lastLetter); // Add '&' before the last letter
}
// Join the shortCode letters together
var departmentCode = shortCode.join('');
// Update the 'department_code' field with the generated code
$('#department_code').val(departmentCode);
});
});

</script>
@endpush
@include('components.media-manager', ['companies' => $companies])

@section('content')

<div class="row">
    <div class="col-12">

        <div class="card">
            <div class="card-header header-elements-inline">
                <h5 class="card-title">{{ $title }}</h5>
                <div class="header-elements"></div>
            </div>

            <div class="card-body">

                <div class="row">
                    <div class="col-12">

                        <form method="POST"
                            action="{{ $item->exists
                                        ? route('settings.departments.update', $item->uuid)
                                        : route('settings.departments.store') }}">
                            @csrf
                            @if($item->exists)
                            @method('PUT')
                            @endif

                            {{-- Name + Parent --}}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name" class="form-label req">
                                            Department Name
                                        </label>
                                        <span class="help">
                                            @if(session()->has('errors'))
                                            {!! session()->get('errors')->first('name') !!}
                                            @endif
                                        </span>
                                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $item->name ?? '') }}" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="parent_id" class="form-label">
                                            Select Parent Department
                                        </label>
                                        <span class="help">
                                            @if(Session::has('errors'))
                                            {!! Session::get('errors')->first('parent_id') !!}
                                            @endif
                                        </span>
                                        <select name="parent_id"
                                            id="parent_id"
                                            class="form-control select2">
                                            <option value="0">This is a parent department</option>
                                            @foreach($departments_dd as $key => $deptName)
                                            <option value="{{ $key }}"
                                                {{ (string) old('parent_id', $item->parent_id ?? '') === (string) $key ? 'selected' : '' }}>
                                                {{ $deptName }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            {{-- Status / Has Website / Prefix --}}
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="department_code" class="form-label"> Department Code </label>
                                        <span class="help">
                                            @if(session()->has('errors'))
                                            {!! session()->get('errors')->first('department_code') !!}
                                            @endif
                                        </span>
                                        <input type="text" name="department_code" id="department_code" class="form-control" value="{{ old('department_code', $item->department_code ?? '') }}">
                                    </div>
                                </div>
                                {{-- Department Type --}}
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label req">Department Type</label>
                                        <span class="help">
                                            @if(Session::has('errors'))
                                            {!! Session::get('errors')->first('department_type') !!}
                                            @endif
                                        </span>
                                        <select name="department_type" id="department_type" class="form-control select2">
                                            <option value="">Select Department Type</option>
                                            @foreach($departmentTypes as $type)
                                            <option value="{{ $type->value }}"
                                                {{ (string) old('department_type', $item->department_type ?? '') === (string) $type->value ? 'selected' : '' }}>
                                                {{ $type->value }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- Status --}}
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label req">Status</label>
                                        <span class="help">
                                            @if(Session::has('errors'))
                                            {!! Session::get('errors')->first('status') !!}
                                            @endif
                                        </span>
                                        @php
                                        $statusValue = old('status', $item->status ?? 'active');
                                        @endphp
                                        <select name="status"
                                            class="form-control"
                                            required>
                                            <option value="active" {{ $statusValue === 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="inactive" {{ $statusValue === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- Has Website
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label req">Has Website</label>
                                        <span class="help">
                                            @if(Session::has('errors'))
                                                {!! Session::get('errors')->first('has_website') !!}
                                            @endif
                                        </span>
                                        @php
                                            $websiteValue = old('has_website', $item->has_website ?? 'no');
                                        @endphp
                                        <select name="has_website"
                                                class="form-control"
                                                required>
                                            <option value="yes" {{ $websiteValue === 'yes' ? 'selected' : '' }}>Yes</option>
                                <option value="no" {{ $websiteValue === 'no'  ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="prefix" class="form-label">
                                Prefix
                            </label>
                            <span class="help">
                                @if(Session::has('errors'))
                                {!! Session::get('errors')->first('prefix') !!}
                                @endif
                            </span>
                            <input type="text"
                                name="prefix"
                                id="prefix"
                                class="form-control"
                                value="{{ old('prefix', $item->prefix ?? '') }}">
                        </div>
                    </div>
                </div>

                {{-- Division / District / Tehsil 
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="division_id" class="form-label">
                                            Division ID
                                        </label>
                                        <span class="help">
                                            @if(Session::has('errors'))
                                                {!! Session::get('errors')->first('division_id') !!}
                                            @endif
                                        </span>
                                        <input type="number"
                                               name="division_id"
                                               id="division_id"
                                               class="form-control"
                                               value="{{ old('division_id', $item->division_id ?? 0) }}">
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="district_id" class="form-label">
                    District ID
                </label>
                <span class="help">
                    @if(Session::has('errors'))
                    {!! Session::get('errors')->first('district_id') !!}
                    @endif
                </span>
                <input type="number"
                    name="district_id"
                    id="district_id"
                    class="form-control"
                    value="{{ old('district_id', $item->district_id ?? 0) }}">
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="tehsil_id" class="form-label">
                    Tehsil ID
                </label>
                <span class="help">
                    @if(Session::has('errors'))
                    {!! Session::get('errors')->first('tehsil_id') !!}
                    @endif
                </span>
                <input type="number"
                    name="tehsil_id"
                    id="tehsil_id"
                    class="form-control"
                    value="{{ old('tehsil_id', $item->tehsil_id ?? 0) }}">
            </div>
        </div>
    </div>--}}

    {{-- Description 
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="description" class="form-label">
                                            Description
                                        </label>
                                        <span class="help">
                                            @if(session()->has('errors'))
                                                {!! session()->get('errors')->first('description') !!}
                                            @endif
                                        </span>
                                        <textarea name="description"
                                                  id="description"
                                                  class="form-control"
                                                  rows="4">{{ old('description', $item->description ?? '') }}</textarea>
</div>
</div>
</div>--}}


{{-- NEW: Department Media + External URL --}}
<div class="row mt-3">
    {{-- Logo / Media --}}
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-label">Department Logo / Media</label>

            <input type="hidden"
                name="media_uuid"
                id="media_uuid"
                value="{{ old('media_uuid', $item->media->uuid ?? '') }}">

            <button type="button"
                class="btn btn-outline-primary open-media-manager"
                data-mode="single"
                data-target-input="#media_uuid"
                data-preview-target="#media_preview">
                Choose Media
            </button>

            <div id="media_preview" class="mt-2">
                @php
                $mUuid = old('media_uuid', $item->media->uuid ?? null);
                @endphp

                @if($mUuid && $item->media)
                @php
                $ext = strtolower($item->media->extension ?? '');
                $url = $item->media->file_path ? asset('storage/' . $item->media->file_path) : null;
                $isImage = in_array($ext, ['jpg','jpeg','png','gif','webp','svg']);
                @endphp

                @if($url)
                @if($isImage)
                <img src="{{ $url }}" class="img-thumbnail" style="max-width: 120px; max-height: 120px; object-fit: cover;">
                @else
                <a href="{{ $url }}" target="_blank" rel="noopener">
                    {{ $item->media->title ?? 'View file' }} ({{ strtoupper($ext) }})
                </a>
                @endif
                @else
                <span class="text-muted">No media file path</span>
                @endif
                @else
                <span class="text-muted">No media selected</span>
                @endif
            </div>
        </div>
    </div>

    {{-- Cover Media --}}
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-label">Cover Media</label>

            <input type="hidden"
                name="cover_media_uuid"
                id="cover_media_uuid"
                value="{{ old('cover_media_uuid', $item->coverMedia->uuid ?? '') }}">

            <button type="button"
                class="btn btn-outline-primary open-media-manager"
                data-mode="single"
                data-target-input="#cover_media_uuid"
                data-preview-target="#cover_media_preview">
                Choose Cover Media
            </button>

            <div id="cover_media_preview" class="mt-2">
                @php
                $cUuid = old('cover_media_uuid', $item->coverMedia->uuid ?? null);
                @endphp

                @if($cUuid && $item->coverMedia)
                @php
                $ext = strtolower($item->coverMedia->extension ?? '');
                $url = $item->coverMedia->file_path ? asset('storage/' . $item->coverMedia->file_path) : null;
                $isImage = in_array($ext, ['jpg','jpeg','png','gif','webp','svg']);
                @endphp

                @if($url)
                @if($isImage)
                <img src="{{ $url }}" class="img-thumbnail" style="max-width: 120px; max-height: 120px; object-fit: cover;">
                @else
                <a href="{{ $url }}" target="_blank" rel="noopener">
                    {{ $item->coverMedia->title ?? 'View file' }} ({{ strtoupper($ext) }})
                </a>
                @endif
                @else
                <span class="text-muted">No media file path</span>
                @endif
                @else
                <span class="text-muted">No cover media selected</span>
                @endif
            </div>
        </div>
    </div>

    {{-- External URL --}}
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-label">External URL</label>
            <span class="help">
                @if(session()->has('errors'))
                {!! session()->get('errors')->first('external_url') !!}
                @endif
            </span>

            <input type="url"
                name="external_url"
                class="form-control"
                value="{{ old('external_url', $item->external_url ?? '') }}"
                placeholder="https://example.com">
        </div>
    </div>
</div>


{{-- Buttons --}}
<div class="row mt-4 ">
    <div class="col-12">
        <a href="{{ route('settings.departments.list') }}"
            class="btn btn-warning">
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

</div>
</div>

@endsection