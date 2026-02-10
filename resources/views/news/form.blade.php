@extends('layouts.' . config('settings.active_layout'))

@push('stylesheets')
    <style>
        /* Hide CKEditor security warning banner */
        .cke_warning {
            display: none !important;
        }
        .cke_notification.cke_notification_warning {
            display: none !important;
        }
        /* Hide any version warning messages */
        .cke_notification[data-name="security-warning"],
        .cke_notification[data-name="version-warning"] {
            display: none !important;
        }
    </style>
@endpush

@section('content')
{{-- ✅ Global media manager (WordPress-style) --}}
@include('components.media-manager', ['companies' => $companies ?? []])
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header header-elements-inline">
                    <h5 class="card-title">{{ $title }}</h5>
                </div>
                <div class="card-body">
                    <form method="POST"
                        action="{{ $item->exists ? route('settings.news.update', $item->uuid) : route('settings.news.store') }}">
                        @csrf
                        @if ($item->exists)
                            @method('PUT')
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label req">Website</label>
                                    <select name="company_id" class="form-control select2" required>
                                        <option value="">Select Website</option>
                                        @foreach ($companies as $id => $name)
                                            <option value="{{ $id }}"
                                                {{ old('company_id', $item->company_id) == $id ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('company_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label req">Title</label>
                                    <input type="text" name="title" class="form-control" id="title"
                                        value="{{ old('title', $item->title) }}" required>
                                    @error('title')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Slug</label>
                                    <input type="text" name="slug" class="form-control" id="slug"
                                        value="{{ old('slug', $item->slug) }}">
                                    <small class="text-muted">Auto-generated from title if left blank.</small>
                                    @error('slug')
                                        <small class="text-danger d-block">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label req">Status</label>
                                    <select name="status" class="form-control" required>
                                        @foreach (['draft', 'scheduled', 'published', 'archived'] as $status)
                                            <option value="{{ $status }}"
                                                {{ old('status', $item->status ?? 'draft') === $status ? 'selected' : '' }}>
                                                {{ ucfirst($status) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label">Summary</label>
                                    <textarea name="summary" class="form-control" rows="3" placeholder="Brief summary of the news article...">{{ old('summary', $item->summary) }}</textarea>
                                    <small class="text-muted">A short summary that appears in news listings and previews.</small>
                                    @error('summary')
                                        <small class="text-danger d-block">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label req">Media Type</label>
                                    <select name="media_type" class="form-control" id="media_type" required>
                                        @foreach (['none' => 'None', 'image' => 'Image', 'video' => 'Video'] as $key => $label)
                                            <option value="{{ $key }}"
                                                {{ old('media_type', $item->media_type ?? 'none') === $key ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('media_type')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6" id="featured_image_field" style="display: none;">
                                <div class="form-group">
                                    <label class="form-label">Featured Image</label>
                                    @error('featured_image_id')
                                    <span class="text-danger d-block">{{ $message }}</span>
                                    @enderror
                                    {{-- hidden field to store selected media UUID --}}
                                    <input type="hidden" name="featured_image_id" id="featured_image_id" value="{{ old('featured_image_id', $item->featuredImage->uuid ?? '') }}">
                                    <button type="button"
                                        class="btn btn-outline-primary open-media-manager"
                                        data-mode="single"
                                        data-target-input="#featured_image_id"
                                        data-preview-target="#featured_image_preview"
                                        data-company-select="#company_id">
                                        Choose Media
                                    </button>

                                    {{-- Preview --}}
                                    <div id="featured_image_preview" class="mt-2">
                                        @php
                                            $featuredImageId = old('featured_image_id', $item->featuredImage->uuid ?? null);
                                        @endphp

                                        @if($featuredImageId && $item->featuredImage)
                                            @php
                                                $ext = strtolower($item->featuredImage->extension ?? '');
                                                $url = $item->featuredImage->external_url ?? ($item->featuredImage->file_path ? asset('storage/' . $item->featuredImage->file_path) : null);
                                                $isImage = in_array($ext, ['jpg','jpeg','png','gif','webp','svg']);
                                            @endphp
                                            @if($url)
                                                @if($isImage)
                                                    <div>
                                                        <img src="{{ $url }}"
                                                            alt="{{ $item->featuredImage->title }}"
                                                            class="img-thumbnail"
                                                            style="max-width: 150px; max-height: 150px; object-fit: cover;">
                                                    </div>
                                                @else
                                                    <div>
                                                        <a href="{{ $url }}" target="_blank" rel="noopener">
                                                            {{ $item->featuredImage->title ?? 'View file' }} ({{ strtoupper($ext) }})
                                                        </a>
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
                            <div class="col-md-6" id="video_url_field" style="display: none;">
                                <div class="form-group">
                                    <label class="form-label">Video URL</label>
                                    <input type="url" name="video_url" class="form-control"
                                        value="{{ old('video_url', $item->video_url) }}">
                                    @error('video_url')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6" id="video_thumbnail_field" style="display: none;">
                                <div class="form-group">
                                    <label class="form-label">Video Thumbnail</label>
                                    @error('video_thumbnail_id')
                                    <span class="text-danger d-block">{{ $message }}</span>
                                    @enderror
                                    {{-- hidden field to store selected media UUID --}}
                                    <input type="hidden" name="video_thumbnail_id" id="video_thumbnail_id" value="{{ old('video_thumbnail_id', $item->videoThumbnail->uuid ?? '') }}">
                                    <button type="button"
                                        class="btn btn-outline-primary open-media-manager"
                                        data-mode="single"
                                        data-target-input="#video_thumbnail_id"
                                        data-preview-target="#video_thumbnail_preview"
                                        data-company-select="#company_id">
                                        Choose Media
                                    </button>

                                    {{-- Preview --}}
                                    <div id="video_thumbnail_preview" class="mt-2">
                                        @php
                                            $videoThumbnailId = old('video_thumbnail_id', $item->videoThumbnail->uuid ?? null);
                                        @endphp

                                        @if($videoThumbnailId && $item->videoThumbnail)
                                            @php
                                                $ext = strtolower($item->videoThumbnail->extension ?? '');
                                                $url = $item->videoThumbnail->external_url ?? ($item->videoThumbnail->file_path ? asset('storage/' . $item->videoThumbnail->file_path) : null);
                                                $isImage = in_array($ext, ['jpg','jpeg','png','gif','webp','svg']);
                                            @endphp
                                            @if($url)
                                                @if($isImage)
                                                    <div>
                                                        <img src="{{ $url }}"
                                                            alt="{{ $item->videoThumbnail->title }}"
                                                            class="img-thumbnail"
                                                            style="max-width: 150px; max-height: 150px; object-fit: cover;">
                                                    </div>
                                                @else
                                                    <div>
                                                        <a href="{{ $url }}" target="_blank" rel="noopener">
                                                            {{ $item->videoThumbnail->title ?? 'View file' }} ({{ strtoupper($ext) }})
                                                        </a>
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

                        {{-- <div class="row mt-3">
                            <div class="col-md-4">
                                <label class="form-label">Published At</label>
                                <input type="datetime-local" name="published_at" class="form-control"
                                    value="{{ old('published_at', optional($item->published_at)->format('Y-m-d\TH:i')) }}">
                                @error('published_at')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Expires At</label>
                                <input type="datetime-local" name="expires_at" class="form-control"
                                    value="{{ old('expires_at', optional($item->expires_at)->format('Y-m-d\TH:i')) }}">
                                @error('expires_at')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-4 d-flex align-items-center">
                                <div class="form-check mt-4">
                                    <input class="form-check-input" type="checkbox" name="is_featured" value="1"
                                        id="is_featured" {{ old('is_featured', $item->is_featured) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_featured">
                                        Featured
                                    </label>
                                </div>
                            </div>
                        </div> --}}

                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label class="form-label mb-0">Body</label>
                                    <button type="button" class="btn btn-primary btn-sm open-media-manager"
                                        data-mode="ckeditor"
                                        data-ckeditor-instance="body-editor"
                                        data-company-select="#company_id">
                                        <i class="bx bx-image"></i> Insert Images
                                    </button>
                                </div>
                                <textarea name="body" id="body-editor" class="form-control" rows="10">{{ old('body', $item->body) }}</textarea>
                                @error('body')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Meta Title</label>
                                    <input type="text" name="meta_title" class="form-control"
                                        value="{{ old('meta_title', $item->meta['title'] ?? '') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Meta Keywords</label>
                                    <input type="text" name="meta_keywords" class="form-control"
                                        value="{{ old('meta_keywords', $item->meta['keywords'] ?? '') }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Meta Description</label>
                                    <textarea name="meta_description" class="form-control" rows="2">{{ old('meta_description', $item->meta['description'] ?? '') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <a href="{{ route('settings.news.list') }}" class="btn btn-secondary">
                                <i class="bx bx-arrow-back"></i> Back
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="bx bx-save"></i> Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.ckeditor.com/4.22.1/full-all/ckeditor.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize CKEditor
            function initCKEditor() {
                if (typeof CKEDITOR !== 'undefined' && $('#body-editor').length) {
                    CKEDITOR.replace('body-editor', {
                        height: 400,
                        extraAllowedContent: 'iframe[*]',
                        extraPlugins: 'image2',
                        // removePlugins: 'exportpdf',
                        removePlugins: 'image',
                        on: {
                            instanceReady: function() {
                                setTimeout(function() {
                                    $('.cke_warning, .cke_notification_warning').remove();
                                }, 500);
                            }
                        }
                    });
                    return true;
                }
                return false;
            }

            // Try to initialize with retry
            if (!initCKEditor()) {
                let attempts = 0;
                const interval = setInterval(function() {
                    if (initCKEditor() || ++attempts >= 10) {
                        clearInterval(interval);
                    }
                }, 200);
            }

            // Slug auto-generation
            let slugChanged = false;
            $('#slug').on('input', function() {
                slugChanged = true;
            });
            $('#title').on('input', function() {
                if (!slugChanged) {
                    $('#slug').val($(this).val().toLowerCase().trim()
                        .replace(/[\s_]+/g, '-')
                        .replace(/[^a-z0-9-]/g, '')
                        .replace(/--+/g, '-'));
                }
            });

            // Show/hide featured image and video URL fields based on media type
            function toggleMediaFields() {
                const mediaType = $('#media_type').val();

                // Hide all fields first
                $('#featured_image_field').hide();
                $('#video_url_field').hide();
                $('#video_thumbnail_field').hide();

                // Show relevant fields based on selection
                if (mediaType === 'image') {
                    $('#featured_image_field').show();
                } else if (mediaType === 'video') {
                    $('#video_url_field').show();
                    $('#video_thumbnail_field').show();
                }
            }

            // Initialize on page load
            toggleMediaFields();

            // Update on change
            $('#media_type').on('change', toggleMediaFields);
        });
    </script>
@endpush
