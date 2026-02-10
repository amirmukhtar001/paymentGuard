@extends('layouts.' . config('settings.active_layout'))

@push('scripts')
<script>
    $(document).ready(function() {

        // Auto-generate slug from title if slug is empty / user hasn't manually typed
        let slugTouched = false;

        $('#slug').on('input', function() {
            slugTouched = true;
        });

        $('#title').on('input', function() {
            if (slugTouched) return;

            const text = $(this).val().trim().toLowerCase();
            const slug = text
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-');

            $('#slug').val(slug);
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
                                    ? route('settings.downloads.update', $item->uuid)
                                    : route('settings.downloads.store') }}">
                            @csrf
                            @if($item->exists)
                                @method('PUT')
                            @endif

                            {{-- Title + Slug --}}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="title" class="form-label req">Download Title</label>
                                        <span class="help">
                                            @if(session()->has('errors'))
                                                {!! session()->get('errors')->first('title') !!}
                                            @endif
                                        </span>
                                        <input type="text"
                                               name="title"
                                               id="title"
                                               class="form-control"
                                               value="{{ old('title', $item->title ?? '') }}"
                                               required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="slug" class="form-label">Slug</label>
                                        <span class="help">
                                            @if(session()->has('errors'))
                                                {!! session()->get('errors')->first('slug') !!}
                                            @endif
                                        </span>
                                        <input type="text"
                                               name="slug"
                                               id="slug"
                                               class="form-control"
                                               value="{{ old('slug', $item->slug ?? '') }}"
                                               placeholder="auto-generated if empty">
                                    </div>
                                </div>
                            </div>

                            {{-- Category / Department / Company --}}
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Category</label>
                                        <span class="help">
                                            @if(session()->has('errors'))
                                                {!! session()->get('errors')->first('category_id') !!}
                                            @endif
                                        </span>
                                        @include('components.categories', [
                                            'categories' => $categories,
                                            'select_id' => 'category_id',
                                            'label' => 'Category',
                                            'selected' => old('category_id', $item->category_id ?? 0),
                                            'placeholder' => 'Select Category'
                                        ])
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Department</label>
                                        <span class="help">
                                            @if(session()->has('errors'))
                                                {!! session()->get('errors')->first('department_id') !!}
                                            @endif
                                        </span>
                                        <select name="department_id" class="form-control select2">
                                            <option value="">Select Department</option>
                                            @foreach($departments as $dept)
                                                <option value="{{ $dept->id }}"
                                                    {{ (string) old('department_id', $item->department_id ?? '') === (string) $dept->id ? 'selected' : '' }}>
                                                    {{ $dept->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Web Site</label>
                                        <span class="help">
                                            @if(session()->has('errors'))
                                                {!! session()->get('errors')->first('company_id') !!}
                                            @endif
                                        </span>

                                        @include('components.company_field', [
                                            'companies' => $companies,
                                            'select_id' => 'company_id',
                                            'label' => 'Web Site'
                                        ])
                                    </div>
                                </div>
                            </div>

                            {{-- Attachment Date / Status --}}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Attachment Date</label>
                                        <span class="help">
                                            @if(session()->has('errors'))
                                                {!! session()->get('errors')->first('attachment_date') !!}
                                            @endif
                                        </span>
                                        <input type="date"
                                               name="attachment_date"
                                               class="form-control"
                                               value="{{ old('attachment_date', optional($item->attachment_date)->format('Y-m-d')) }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label req">Status</label>
                                        <span class="help">
                                            @if(session()->has('errors'))
                                                {!! session()->get('errors')->first('status') !!}
                                            @endif
                                        </span>

                                        @php
                                            // If enum cast, $item->status may be object; handle both
                                            $statusOld = old('status', is_object($item->status ?? null)
                                                ? ($item->status->value ?? 'Active')
                                                : ($item->status ?? 'Active'));
                                        @endphp

                                        <select name="status" class="form-control" required>
                                            <option value="Active" {{ $statusOld === 'Active' ? 'selected' : '' }}>Active</option>
                                            <option value="InActive" {{ $statusOld === 'InActive' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            {{-- Media + Description --}}
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Attachment / Media</label>

                                        {{-- IMPORTANT: send media UUID in this field (request converts it to id) --}}
                                        <input type="hidden"
                                               name="media_id"
                                               id="media_id"
                                               value="{{ old('media_id', optional($item->media)->uuid) }}">

                                        <button type="button"
                                                class="btn btn-outline-primary open-media-manager"
                                                data-mode="single"
                                                data-target-input="#media_id"
                                                data-preview-target="#media_preview">
                                            Choose Media
                                        </button>

                                        <div id="media_preview" class="mt-2">
                                            @php
                                                $mUuid = old('media_id', optional($item->media)->uuid);
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

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Description</label>
                                        <span class="help">
                                            @if(session()->has('errors'))
                                                {!! session()->get('errors')->first('description') !!}
                                            @endif
                                        </span>
                                        <textarea name="description"
                                                  class="form-control"
                                                  rows="3"
                                                  placeholder="Optional">{{ old('description', $item->description ?? '') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            {{-- Buttons --}}
                            <div class="row mt-4">
                                <div class="col-12">
                                    <a href="{{ route('settings.downloads.index') }}"
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
