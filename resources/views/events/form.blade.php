@extends('layouts.' . config('settings.active_layout'))

@push('stylesheets')
    <style>
        .cke_warning,
        .cke_notification.cke_notification_warning,
        .cke_notification[data-name="security-warning"],
        .cke_notification[data-name="version-warning"] {
            display: none !important;
        }
    </style>
@endpush

@section('content')
    {{-- Global media manager (WordPress-style) --}}
    @include('components.media-manager', ['companies' => $companies ?? []])
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header header-elements-inline">
                    <h5 class="card-title">{{ $title }}</h5>
                </div>
                <div class="card-body">
                    <form method="POST"
                        action="{{ $item->exists ? route('settings.events.update', $item->uuid) : route('settings.events.store') }}">
                        @csrf
                        @if ($item->exists)
                            @method('PUT')
                        @endif

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label req">Website</label>
                                    <select name="company_id" id="company_id" class="form-control select2" required>
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
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Category</label>
                                    <select name="category_id" id="category_id" class="form-control select2">
                                        <option value="">Select Category</option>
                                        @foreach ($categories ?? [] as $id => $name)
                                            <option value="{{ $id }}"
                                                {{ old('category_id', $item->category_id) == $id ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
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

                        <div class="row mt-3">
                            <div class="col-md-4">
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
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label req">Status</label>
                                    <select name="status" class="form-control" required>
                                        @foreach (['draft' => 'Draft', 'pending' => 'Pending', 'publish' => 'Publish'] as $key => $label)
                                            <option value="{{ $key }}"
                                                {{ old('status', $item->status ?? 'publish') === $key ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
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

                        {{-- Event Specific Fields --}}
                        <div class="row mt-3">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Event Date</label>
                                    <input type="date" name="event_date" class="form-control"
                                        value="{{ old('event_date', $item->event_date ? $item->event_date->format('Y-m-d') : '') }}">
                                    @error('event_date')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Event End Date</label>
                                    <input type="date" name="event_end_date" class="form-control"
                                        value="{{ old('event_end_date', $item->event_end_date ? $item->event_end_date->format('Y-m-d') : '') }}">
                                    @error('event_end_date')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Location</label>
                                    <input type="text" name="location" class="form-control"
                                        value="{{ old('location', $item->location) }}" placeholder="Event location">
                                    @error('location')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Thumbnail</label>
                                    @error('thumbnail_id')
                                    <span class="text-danger d-block">{{ $message }}</span>
                                    @enderror
                                    <input type="hidden" name="thumbnail_id" id="thumbnail_id" value="{{ old('thumbnail_id', $item->thumbnail->uuid ?? '') }}">
                                    <input type="hidden" name="remove_thumbnail" id="remove_thumbnail" value="0">
                                    <button type="button"
                                        class="btn btn-outline-primary open-media-manager"
                                        data-mode="single"
                                        data-target-input="#thumbnail_id"
                                        data-preview-target="#thumbnail_preview"
                                        data-company-select="#company_id">
                                        Choose Media
                                    </button>

                                    <div id="thumbnail_preview" class="mt-2">
                                        @php
                                            $thumbnailId = old('thumbnail_id', $item->thumbnail->uuid ?? null);
                                        @endphp

                                        @if($thumbnailId && $item->thumbnail)
                                            @php
                                                $ext = strtolower($item->thumbnail->extension ?? '');
                                                $url = $item->thumbnail->external_url ?? ($item->thumbnail->file_path ? asset('storage/' . $item->thumbnail->file_path) : null);
                                                $isImage = in_array($ext, ['jpg','jpeg','png','gif','webp','svg']);
                                            @endphp
                                            @if($url)
                                                @if($isImage)
                                                    <div class="position-relative d-inline-block">
                                                        <img src="{{ $url }}"
                                                            alt="{{ $item->thumbnail->title }}"
                                                            class="img-thumbnail"
                                                            style="max-width: 150px; max-height: 150px; object-fit: cover;">
                                                        <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1" 
                                                                onclick="$('#thumbnail_id').val(''); $('#remove_thumbnail').val('1'); $('#thumbnail_preview').html('<span class=\'text-muted\'>No media selected</span>');" 
                                                                style="z-index: 10;">×</button>
                                                    </div>
                                                @else
                                                    <div class="position-relative d-inline-block">
                                                        <a href="{{ $url }}" target="_blank" rel="noopener">
                                                            {{ $item->thumbnail->title ?? 'View file' }} ({{ strtoupper($ext) }})
                                                        </a>
                                                        <button type="button" class="btn btn-sm btn-danger ms-2" 
                                                                onclick="$('#thumbnail_id').val(''); $('#remove_thumbnail').val('1'); $('#thumbnail_preview').html('<span class=\'text-muted\'>No media selected</span>');" 
                                                                style="z-index: 10;">×</button>
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
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Event Gallery Images</label>
                                    @error('gallery_images')
                                    <span class="text-danger d-block">{{ $message }}</span>
                                    @enderror
                                    @php
                                        $galleryImages = old('gallery_images', $item->gallery_images ?? []);
                                        if (is_string($galleryImages)) {
                                            $galleryImages = json_decode($galleryImages, true) ?? [];
                                        }
                                        if (!is_array($galleryImages)) {
                                            $galleryImages = [];
                                        }
                                        $galleryImagesMedia = $galleryImagesMedia ?? collect();
                                        if ($galleryImagesMedia->isEmpty() && !empty($galleryImages)) {
                                            $galleryImagesMedia = \App\Models\Web\Media::whereIn('uuid', $galleryImages)->get();
                                        }
                                    @endphp
                                    <input type="hidden" name="gallery_images" id="gallery_images" value="{{ json_encode($galleryImages) }}">
                                    <button type="button"
                                        class="btn btn-outline-primary open-media-manager"
                                        data-mode="multiple"
                                        data-target-input="#gallery_images"
                                        data-preview-target="#gallery_images_preview"
                                        data-company-select="#company_id">
                                        Choose Gallery Images
                                    </button>
                                    <div id="gallery_images_preview" class="mt-2">
                                        @if($galleryImagesMedia->isNotEmpty())
                                            <div class="row g-2" id="gallery_images_list">
                                                @foreach($galleryImagesMedia as $image)
                                                    <div class="col-md-3 gallery-image-item" data-uuid="{{ $image->uuid }}" style="cursor: move;">
                                                        <div class="position-relative">
                                                            @php
                                                                $url = $image->file_path ? asset('storage/' . $image->file_path) : ($image->external_url ?? '');
                                                            @endphp
                                                            @if($url)
                                                                <img src="{{ $url }}" alt="{{ $image->title }}" class="img-thumbnail w-100" style="height: 100px; object-fit: cover;">
                                                                <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 remove-gallery-image" data-uuid="{{ $image->uuid }}" style="z-index: 10;">×</button>
                                                                <span class="badge bg-secondary position-absolute top-0 start-0 m-1" style="z-index: 5;"><i class="bx bx-move"></i></span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-muted">No gallery images selected</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Attached File</label>
                                    @error('attached_file')
                                    <span class="text-danger d-block">{{ $message }}</span>
                                    @enderror
                                    @php
                                        // Get attached file UUID from model (stored as JSON array with single value)
                                        $attachedFile = '';
                                        if (!empty($item->attached_files)) {
                                            if (is_array($item->attached_files)) {
                                                $attachedFile = $item->attached_files[0] ?? '';
                                            } elseif (is_string($item->attached_files)) {
                                                $decoded = json_decode($item->attached_files, true);
                                                $attachedFile = is_array($decoded) && !empty($decoded) ? ($decoded[0] ?? '') : '';
                                            }
                                        }

                                        // Override with old input if exists
                                        $attachedFile = old('attached_file', $attachedFile);

                                        // If we have pre-loaded media collection, use it; otherwise look up by UUID
                                        $attachedFileMedia = $attachedFilesMedia->first();
                                        if (!$attachedFileMedia && !empty($attachedFile)) {
                                            $attachedFileMedia = \App\Models\Web\Media::where('uuid', $attachedFile)->first();
                                        }
                                    @endphp
                                    <input type="hidden" name="attached_file" id="attached_file" value="{{ $attachedFile }}">
                                    <input type="hidden" name="remove_attached_file" id="remove_attached_file" value="0">
                                    <button type="button"
                                        class="btn btn-outline-primary open-media-manager"
                                        data-mode="single"
                                        data-target-input="#attached_file"
                                        data-preview-target="#attached_file_preview"
                                        data-company-select="#company_id">
                                        Choose File
                                    </button>

                                    <div id="attached_file_preview" class="mt-2">
                                        @if($attachedFileMedia)
                                            @php
                                                $url = $attachedFileMedia->file_path ? asset('storage/' . $attachedFileMedia->file_path) : ($attachedFileMedia->external_url ?? '');
                                                $ext = strtolower($attachedFileMedia->extension ?? '');
                                                $isImage = in_array($ext, ['jpg','jpeg','png','gif','webp','svg']);
                                            @endphp
                                            @if($url)
                                                @if($isImage)
                                                    <div class="position-relative d-inline-block">
                                                        <img src="{{ $url }}" alt="{{ $attachedFileMedia->title }}" class="img-thumbnail" style="max-width: 150px; max-height: 150px; object-fit: cover;">
                                                        <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1" 
                                                                onclick="$('#attached_file').val(''); $('#remove_attached_file').val('1'); $('#attached_file_preview').html('<span class=\'text-muted\'>No file selected</span>');" 
                                                                style="z-index: 10;">×</button>
                                                    </div>
                                                @else
                                                    <div class="position-relative d-inline-block">
                                                        <a href="{{ $url }}" target="_blank" rel="noopener">
                                                            {{ $attachedFileMedia->title ?? $attachedFileMedia->file_name ?? 'View file' }} ({{ strtoupper($ext) }})
                                                        </a>
                                                        <button type="button" class="btn btn-sm btn-danger ms-2" 
                                                                onclick="$('#attached_file').val(''); $('#remove_attached_file').val('1'); $('#attached_file_preview').html('<span class=\'text-muted\'>No file selected</span>');" 
                                                                style="z-index: 10;">×</button>
                                                    </div>
                                                @endif
                                            @else
                                                <span class="text-muted">No file path</span>
                                            @endif
                                        @else
                                            <span class="text-muted">No file selected</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Summary field - commented out as not required for now --}}
                        {{-- <div class="row mt-3">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label">Summary</label>
                                    <textarea name="summary" class="form-control" rows="3"
                                        placeholder="Brief summary of the event...">{{ old('summary', $item->summary) }}</textarea>
                                    <small class="text-muted">A short summary that appears in event listings and previews.</small>
                                    @error('summary')
                                        <small class="text-danger d-block">{{ $message }}</small>
                                    @enderror
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
                                    <label class="form-label">Meta Key</label>
                                    <input type="text" name="meta_key" class="form-control"
                                        value="{{ old('meta_key', $item->meta_key) }}">
                                    @error('meta_key')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Meta Value</label>
                                    <textarea name="meta_value" class="form-control" rows="2">{{ old('meta_value', $item->meta_value) }}</textarea>
                                    @error('meta_value')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <a href="{{ route('settings.events.list') }}" class="btn btn-secondary">
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

@push('styles')
    <style>
        .sortable-ghost {
            opacity: 0.4;
            background: #f8f9fa;
        }
        .gallery-image-item {
            transition: transform 0.2s;
        }
        .gallery-image-item:active {
            cursor: grabbing !important;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.ckeditor.com/4.22.1/full-all/ckeditor.js"></script>
    <script>
        $(document).ready(function() {
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

            if (!initCKEditor()) {
                let attempts = 0;
                const interval = setInterval(function() {
                    if (initCKEditor() || ++attempts >= 10) {
                        clearInterval(interval);
                    }
                }, 200);
            }

            // Reset removal flags when new media is selected
            $('#thumbnail_id').on('change', function() {
                if ($(this).val()) {
                    $('#remove_thumbnail').val('0');
                }
            });

            $('#attached_file').on('change', function() {
                if ($(this).val()) {
                    $('#remove_attached_file').val('0');
                }
            });

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

            // Remove gallery image handler
            $(document).on('click', '.remove-gallery-image', function() {
                const uuid = $(this).data('uuid');
                let images = JSON.parse($('#gallery_images').val() || '[]');
                images = images.filter(i => i !== uuid);
                $('#gallery_images').val(JSON.stringify(images));
                $(this).closest('.col-md-3').remove();

                if (images.length === 0) {
                    $('#gallery_images_preview').html('<span class="text-muted">No gallery images selected</span>');
                }
            });

            // Store gallery_images value before opening media manager
            let galleryImagesBackup = null;
            
            $(document).on('click', '.open-media-manager[data-target-input="#gallery_images"]', function() {
                // Backup current value before media manager opens
                const currentVal = $('#gallery_images').val();
                try {
                    galleryImagesBackup = currentVal ? JSON.parse(currentVal) : [];
                    if (!Array.isArray(galleryImagesBackup)) {
                        galleryImagesBackup = [];
                    }
                } catch(e) {
                    galleryImagesBackup = [];
                }
            });

            // Fix gallery_images value format when media manager sets it (converts comma-separated to JSON array)
            // AND append new selections to existing ones instead of replacing
            let isProcessingGalleryChange = false; // Prevent infinite loop
            
            $(document).on('change', '#gallery_images', function() {
                if (isProcessingGalleryChange) return;
                
                const val = $(this).val();
                
                // Check if value is from media manager (comma-separated string or single ID, not JSON)
                if (val && typeof val === 'string' && !val.startsWith('[') && !val.startsWith('{')) {
                    isProcessingGalleryChange = true;
                    
                    // Split by comma (handles both single and multiple selections)
                    const newIds = val.includes(',') ? val.split(',').filter(id => id.trim()) : [val.trim()];
                    
                    // Merge with backup (existing images before media manager opened)
                    const existingIds = galleryImagesBackup || [];
                    
                    // Check for duplicates
                    const duplicates = newIds.filter(id => existingIds.includes(id));
                    if (duplicates.length > 0) {
                        const message = duplicates.length === 1 
                            ? '1 image was already selected and was skipped.'
                            : `${duplicates.length} images were already selected and were skipped.`;
                        
                        // Show toast notification (if available) or Bootstrap alert
                        if (typeof toastr !== 'undefined') {
                            toastr.info(message, 'Duplicate Images');
                        } else {
                            const $notification = $('<div class="alert alert-info alert-dismissible fade show position-fixed" style="top: 20px; right: 20px; z-index: 9999; max-width: 300px;" role="alert">' +
                                '<strong>Duplicate Images:</strong> ' + message +
                                '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
                                '</div>');
                            $('body').append($notification);
                            setTimeout(() => $notification.fadeOut(() => $notification.remove()), 4000);
                        }
                    }
                    
                    const mergedIds = [...existingIds, ...newIds];
                    const uniqueIds = [...new Set(mergedIds)]; // Remove duplicates
                    
                    $(this).val(JSON.stringify(uniqueIds));
                    
                    // Update backup for next time
                    galleryImagesBackup = uniqueIds;
                    
                    isProcessingGalleryChange = false;
                }
            });

            // Update previews after media manager modal closes
            $(document).on('hidden.bs.modal', '#mediaManagerModal', function() {
                setTimeout(function() {
                    // Fix gallery_images format if needed
                    const galleryVal = $('#gallery_images').val();
                    if (galleryVal && typeof galleryVal === 'string' && galleryVal.includes(',') && !galleryVal.startsWith('[')) {
                        const ids = galleryVal.split(',').filter(id => id.trim());
                        $('#gallery_images').val(JSON.stringify(ids));
                    }

                    updateGalleryImagesPreview();
                    updateAttachedFilePreview();
                }, 200);
            });

            function updateGalleryImagesPreview() {
                const galleryImagesVal = $('#gallery_images').val();
                if (!galleryImagesVal) {
                    $('#gallery_images_preview').html('<span class="text-muted">No gallery images selected</span>');
                    return;
                }

                let galleryImages = [];
                try {
                    galleryImages = JSON.parse(galleryImagesVal);
                } catch(e) {
                    galleryImages = [];
                }

                const previewContainer = $('#gallery_images_preview');

                if (!Array.isArray(galleryImages) || galleryImages.length === 0) {
                    previewContainer.html('<span class="text-muted">No gallery images selected</span>');
                    return;
                }

                // Fetch media data for selected UUIDs
                const companyId = $('#company_id').val();
                if (!companyId) {
                    previewContainer.html('<span class="text-warning">Please select a website first</span>');
                    return;
                }

                $.ajax({
                    url: '{{ route('settings.events.images') }}',
                    type: 'GET',
                    data: {
                        company_id: companyId,
                        uuids: galleryImages.join(',')
                    },
                    success: function(images) {
                        if (images && images.length > 0) {
                            // Create a map of images by UUID for quick lookup
                            const imageMap = {};
                            images.forEach(function(image) {
                                imageMap[image.id] = image;
                            });
                            
                            // Build HTML in the order specified by galleryImages array
                            let html = '<div class="row g-2" id="gallery_images_list">';
                            galleryImages.forEach(function(uuid) {
                                const image = imageMap[uuid];
                                if (image) {
                                    const url = image.url || image.thumbnail || '';
                                    if (url) {
                                        html += `
                                            <div class="col-md-3 gallery-image-item" data-uuid="${image.id}" style="cursor: move;">
                                                <div class="position-relative">
                                                    <img src="${url}" alt="${image.title || ''}" class="img-thumbnail w-100" style="height: 100px; object-fit: cover;">
                                                    <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 remove-gallery-image" data-uuid="${image.id}" style="z-index: 10;">×</button>
                                                    <span class="badge bg-secondary position-absolute top-0 start-0 m-1" style="z-index: 5;"><i class="bx bx-move"></i></span>
                                                </div>
                                            </div>
                                        `;
                                    }
                                }
                            });
                            html += '</div>';
                            previewContainer.html(html);
                            initializeGallerySortable(); // Enable drag-and-drop
                        } else {
                            previewContainer.html('<span class="text-muted">No gallery images selected</span>');
                        }
                    },
                    error: function() {
                        previewContainer.html('<span class="text-danger">Error loading preview</span>');
                    }
                });
            }

            function updateAttachedFilePreview() {
                const attachedFileUuid = $('#attached_file').val();
                const previewContainer = $('#attached_file_preview');

                if (!attachedFileUuid) {
                    previewContainer.html('<span class="text-muted">No file selected</span>');
                    return;
                }

                // Fetch media data for selected UUID
                const companyId = $('#company_id').val();
                if (!companyId) {
                    previewContainer.html('<span class="text-warning">Please select a website first</span>');
                    return;
                }

                $.ajax({
                    url: '{{ route('settings.events.images') }}',
                    type: 'GET',
                    data: {
                        company_id: companyId,
                        uuid: attachedFileUuid
                    },
                    success: function(files) {
                        const file = Array.isArray(files) ? files[0] : files;
                        if (file && file.url) {
                            const ext = (file.extension || '').toLowerCase();
                            const isImage = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'].includes(ext);
                            let html = '';

                            if (isImage) {
                                html = `<div class="position-relative d-inline-block">
                                    <img src="${file.url}" alt="${file.title || ''}" class="img-thumbnail" style="max-width: 150px; max-height: 150px; object-fit: cover;">
                                    <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1" 
                                            onclick="$('#attached_file').val(''); $('#remove_attached_file').val('1'); $('#attached_file_preview').html('<span class=\'text-muted\'>No file selected</span>');" 
                                            style="z-index: 10;">×</button>
                                </div>`;
                            } else {
                                html = `<div class="position-relative d-inline-block">
                                    <a href="${file.url}" target="_blank" rel="noopener">${file.title || file.file_name || 'View file'}${ext ? ' (' + ext.toUpperCase() + ')' : ''}</a>
                                    <button type="button" class="btn btn-sm btn-danger ms-2" 
                                            onclick="$('#attached_file').val(''); $('#remove_attached_file').val('1'); $('#attached_file_preview').html('<span class=\'text-muted\'>No file selected</span>');" 
                                            style="z-index: 10;">×</button>
                                </div>`;
                            }
                            previewContainer.html(html);
                        } else {
                            previewContainer.html('<span class="text-muted">No file selected</span>');
                        }
                    },
                    error: function() {
                        previewContainer.html('<span class="text-danger">Error loading preview</span>');
                    }
                });
            }

            // Dynamic category loading based on company_id
            $('#company_id').on('change', function() {
                const companyId = $(this).val();
                const categorySelect = $('#category_id');
                categorySelect.empty().append('<option value="">Loading...</option>');

                if (companyId) {
                    $.ajax({
                        url: '{{ route('settings.events.categories') }}',
                        type: 'GET',
                        data: { company_id: companyId },
                        success: function(response) {
                            categorySelect.empty().append('<option value="">Select Category</option>');
                            $.each(response, function(id, title) {
                                categorySelect.append(new Option(title, id));
                            });
                            // Re-select old value if available
                            const oldCategoryId = "{{ old('category_id', $item->category_id) }}";
                            if (oldCategoryId) {
                                categorySelect.val(oldCategoryId).trigger('change');
                            }
                        },
                        error: function() {
                            categorySelect.empty().append('<option value="">Error loading categories</option>');
                        }
                    });
                } else {
                    categorySelect.empty().append('<option value="">Select Category</option>');
                }
            }).trigger('change'); // Trigger on load for edit mode
            
            // Initialize native HTML5 drag-and-drop for gallery images
            function initializeGallerySortable() {
                const galleryList = document.getElementById('gallery_images_list');
                if (!galleryList) return;
                
                let draggedElement = null;
                
                // Add drag event listeners to all gallery items
                const items = galleryList.querySelectorAll('.gallery-image-item');
                items.forEach(item => {
                    item.setAttribute('draggable', 'true');
                    
                    item.addEventListener('dragstart', function(e) {
                        draggedElement = this;
                        this.style.opacity = '0.4';
                        e.dataTransfer.effectAllowed = 'move';
                    });
                    
                    item.addEventListener('dragend', function(e) {
                        this.style.opacity = '1';
                    });
                    
                    item.addEventListener('dragover', function(e) {
                        e.preventDefault();
                        e.dataTransfer.dropEffect = 'move';
                        
                        if (draggedElement !== this) {
                            const rect = this.getBoundingClientRect();
                            const midpoint = rect.left + rect.width / 2;
                            
                            if (e.clientX < midpoint) {
                                this.parentNode.insertBefore(draggedElement, this);
                            } else {
                                this.parentNode.insertBefore(draggedElement, this.nextSibling);
                            }
                        }
                    });
                    
                    item.addEventListener('drop', function(e) {
                        e.preventDefault();
                        updateGalleryImagesOrder();
                    });
                });
            }
            
            // Update gallery_images hidden input with new order
            function updateGalleryImagesOrder() {
                const orderedUuids = [];
                $('#gallery_images_list .gallery-image-item').each(function() {
                    const uuid = $(this).data('uuid');
                    if (uuid) {
                        orderedUuids.push(uuid);
                    }
                });
                
                $('#gallery_images').val(JSON.stringify(orderedUuids));
                galleryImagesBackup = orderedUuids; // Update backup
            }
            
            
            // Initialize on page load if gallery images exist
            initializeGallerySortable();
        });
    </script>
@endpush
