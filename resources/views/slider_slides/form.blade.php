@extends('layouts.' . config('settings.active_layout'))

@section('content')
{{-- ✅ Global media manager (WordPress-style) --}}
@include('components.media-manager', ['companies' => $companies])
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
                                        ? route('settings.slider_slides.update', $item->uuid)
                                        : route('settings.slider_slides.store') }}">
                            @csrf
                            @if($item->exists)
                            @method('PUT')
                            @endif

                            {{-- Slider --}}
                            <div class="row">
                                <div class="col-md-4">
                                    <input name="slider_uuid" value="{{ $uuid }}" type="hidden">
                                    <div class="form-group">
                                        <label class="form-label req">Slider</label>
                                        <span class="help">
                                            @if($errors->has('slider_id')) {!! $errors->first('slider_id') !!} @endif
                                        </span>
                                        <select name="slider_id"
                                            id="slider_id"
                                            class="form-control select2"
                                            required>
                                            <option value="">Select Slider</option>
                                            @foreach($sliders as $id => $name)
                                            <option value="{{ $id }}"
                                                 {{ (string)old('slider_id', $item->slider_id ?? ($sliderId ?? '')) === (string)$id ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- Title --}}
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Title</label>
                                        <span class="help">
                                            @if($errors->has('title')) {!! $errors->first('title') !!} @endif
                                        </span>
                                        <input type="text"
                                            name="title"
                                            id="title"
                                            class="form-control"
                                            value="{{ old('title', $item->title ?? '') }}">
                                    </div>
                                </div>

                                {{-- Media --}}
                                {{-- ✅ Media (using modal + preview now) --}}
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Media</label>
                                        @error('media_id')
                                        <span class="text-danger d-block">{{ $message }}</span>
                                        @enderror
                                        {{-- hidden field to store selected media ID --}}
                                        <input type="hidden" name="media_id" id="media_id" value="{{ old('media_id', $item->media->uuid ?? '') }}">
                                        <button type="button"
                                            class="btn btn-outline-primary open-media-manager"
                                            data-mode="single"
                                            data-target-input="#media_id"
                                            data-preview-target="#slider_media_preview">
                                            Choose Media
                                        </button>

                                        {{-- Preview --}}
                                        <div id="slider_media_preview" class="mt-2">
                                            @php
                                            $mediaId = old('media_id', $item->media->uuid ?? null);
                                            @endphp

                                            @if($mediaId && $item->media)
                                            @php
                                            $ext = strtolower($item->media->extension ?? '');
                                            $url = $item->media->file_path ? asset('storage/' . $item->media->file_path) : null;
                                            $isImage = in_array($ext, ['jpg','jpeg','png','gif','webp','svg']);
                                            @endphp
                                            @if($url)
                                            @if($isImage)
                                            <div>
                                                <img src="{{ $url }}"
                                                    alt="{{ $item->media->title }}"
                                                    class="img-thumbnail"
                                                    style="max-width: 150px; max-height: 150px; object-fit: cover;">
                                                <!-- <div class="small text-muted mt-1">
                                                    ID: {{ $mediaId }} | {{ $item->media->title }}
                                                </div> -->
                                            </div>
                                            @else
                                            <div>
                                                <a href="{{ $url }}" target="_blank" rel="noopener">
                                                    {{ $item->media->title ?? 'View file' }} ({{ strtoupper($ext) }})
                                                </a>
                                                <div class="small text-muted mt-1">
                                                    ID: {{ $item->media->uuid }}
                                                </div>
                                            </div>
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
                            </div>

                            {{-- Caption & Button Text --}}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Caption</label>
                                        <textarea name="caption"
                                            class="form-control"
                                            rows="3">{{ old('caption', $item->caption ?? '') }}</textarea>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Button Text</label>
                                        <input type="text"
                                            name="button_text"
                                            class="form-control"
                                            value="{{ old('button_text', $item->button_text ?? '') }}">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Button URL</label>
                                        <input type="url"
                                            name="button_url"
                                            class="form-control"
                                            value="{{ old('button_url', $item->button_url ?? '') }}">
                                    </div>
                                </div>
                            </div>

                            {{-- Active Status, Sort Order, and Schedule --}}
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label req">Active</label>
                                        <select name="is_active" class="form-control" required>
                                            <option value="1" {{ old('is_active', $item->is_active ?? 1) == 1 ? 'selected' : '' }}>Active</option>
                                            <option value="0" {{ old('is_active', $item->is_active ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Sort Order</label>
                                        <input type="number"
                                            name="sort_order"
                                            class="form-control"
                                            value="{{ old('sort_order', $item->sort_order ?? 0) }}">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Overlay Opacity</label>
                                        <input type="number"
                                            name="overlay_opacity"
                                            step="0.01"
                                            min="0"
                                            max="1"
                                            class="form-control"
                                            value="{{ old('overlay_opacity', $item->overlay_opacity ?? '') }}">
                                    </div>
                                </div>
                            </div>

                            {{-- Schedule Start & End --}}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Schedule Start</label>
                                        <input type="datetime-local"
                                            name="schedule_start"
                                            class="form-control"
                                            value="{{ old('schedule_start', optional($item->schedule_start)->format('Y-m-d\TH:i')) }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Schedule End</label>
                                        <input type="datetime-local"
                                            name="schedule_end"
                                            class="form-control"
                                            value="{{ old('schedule_end', optional($item->schedule_end)->format('Y-m-d\TH:i')) }}">
                                    </div>
                                </div>
                            </div>

                            {{-- Meta (JSON) --}}
                            <div class="form-group">
                                <label class="form-label">Meta (Optional, JSON format)</label>
                                <textarea name="meta"
                                    class="form-control"
                                    rows="3">{{ old('meta', json_encode($item->meta ?? [])) }}</textarea>
                            </div>

                            {{-- Buttons --}}
                            <div class="row mt-3">
                                <div class="col-12">
                                    <a href="{{ route('settings.slider_slides.list') }}" class="btn btn-warning">
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

@push('scripts')
<script>
    $(document).ready(function() {
        let slugManuallyChanged = false;

        // Function to slugify text
        function slugify(text) {
            return text
                .toString()
                .toLowerCase()
                .trim()
                .replace(/[\s_]+/g, '-') // spaces/underscores -> -
                .replace(/[^a-z0-9\-]+/g, '') // remove non-alphanumeric/hyphen
                .replace(/\-\-+/g, '-'); // collapse multiple -
        }

        $('#title').on('input change', function() {
            // Automatically slugify the title if not manually changed
            if (!slugManuallyChanged) {
                const title = $(this).val();
                $('#slug').val(slugify(title));
            }
        });

        // Prevent slug modification if done manually
        $('#slug').on('input', function() {
            slugManuallyChanged = true;
        });
    });
</script>
@endpush