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
                        action="{{ $item->exists ? route('settings.services.update', $item->uuid) : route('settings.services.store') }}">
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
                                    <label class="form-label">Department</label>
                                    <select name="department_id" id="department_id" class="form-control select2">
                                        <option value="">Select Department</option>
                                        @foreach ($departments ?? [] as $id => $name)
                                            <option value="{{ $id }}"
                                                {{ old('department_id', $item->department_id ?? '') == $id ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('department_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Parent Category</label>
                                    <select name="parent_category_id" id="parent_category_id" class="form-control select2">
                                        <option value="">Select Parent Category</option>
                                        @foreach ($categories ?? [] as $id => $name)
                                            <option value="{{ $id }}"
                                                {{ old('parent_category_id', $parentCategoryId ?? '') == $id ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Sub Category</label>
                                    <select name="sub_category_ids[]" id="sub_category_ids" class="form-control select2" multiple>
                                        {{-- Populated via AJAX --}}
                                    </select>
                                    @error('sub_category_ids')
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
                                                {{ old('status', $item->status ?? 'draft') === $key ? 'selected' : '' }}>
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
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">External URL</label>
                                    <input type="url" name="external_url" class="form-control"
                                        value="{{ old('external_url', $item->external_url) }}">
                                    @error('external_url')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label d-flex justify-content-between align-items-center">
                                        <span>Thumbnail</span>
                                        @if($item->exists && $item->thumbnail)
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-danger"
                                                    id="remove_thumbnail_btn">
                                                Remove
                                            </button>
                                        @endif
                                    </label>
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
                                                    <div>
                                                        <img src="{{ $url }}"
                                                            alt="{{ $item->thumbnail->title }}"
                                                            class="img-thumbnail"
                                                            style="max-width: 150px; max-height: 150px; object-fit: cover;">
                                                    </div>
                                                @else
                                                    <div>
                                                        <a href="{{ $url }}" target="_blank" rel="noopener">
                                                            {{ $item->thumbnail->title ?? 'View file' }} ({{ strtoupper($ext) }})
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
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Attached Files</label>
                                    @error('attached_files')
                                    <span class="text-danger d-block">{{ $message }}</span>
                                    @enderror
                                    @php
                                        // Get attached files - handle both old input and model data
                                        $attachedFiles = old('attached_files', $item->attached_files ?? []);

                                        // If it's a string (from old input or JSON), decode it
                                        if (is_string($attachedFiles)) {
                                            $attachedFiles = json_decode($attachedFiles, true) ?? [];
                                        }

                                        // Ensure it's an array
                                        if (!is_array($attachedFiles)) {
                                            $attachedFiles = [];
                                        }

                                        // If we have pre-loaded media collection, use it; otherwise look up by UUID
                                        $attachedFilesMedia = $attachedFilesMedia ?? collect();
                                        if ($attachedFilesMedia->isEmpty() && !empty($attachedFiles)) {
                                            // Fallback: look up media by UUIDs
                                            $attachedFilesMedia = \App\Models\Web\Media::whereIn('uuid', $attachedFiles)->get();
                                        }
                                    @endphp
                                    <input type="hidden" name="attached_files" id="attached_files" value="{{ json_encode($attachedFiles) }}">
                                    <button type="button"
                                        class="btn btn-outline-primary open-media-manager"
                                        data-mode="multiple"
                                        data-target-input="#attached_files"
                                        data-preview-target="#attached_files_preview"
                                        data-company-select="#company_id">
                                        Choose Files
                                    </button>

                                    <div id="attached_files_preview" class="mt-2">
                                        @if($attachedFilesMedia->isNotEmpty())
                                            <div class="list-group" id="attached_files_list">
                                                @foreach($attachedFilesMedia as $file)
                                                    <div class="list-group-item d-flex justify-content-between align-items-center" data-uuid="{{ $file->uuid }}">
                                                        <span>{{ $file->title ?? $file->file_name }}</span>
                                                        <button type="button" class="btn btn-sm btn-danger remove-file" data-uuid="{{ $file->uuid }}">Remove</button>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @elseif(!empty($attachedFiles))
                                            {{-- Fallback: if we have UUIDs but no media objects, try to look them up --}}
                                            <div class="list-group" id="attached_files_list">
                                                @foreach($attachedFiles as $fileUuid)
                                                    @php
                                                        $file = \App\Models\Web\Media::where('uuid', $fileUuid)->first();
                                                    @endphp
                                                    @if($file)
                                                        <div class="list-group-item d-flex justify-content-between align-items-center" data-uuid="{{ $fileUuid }}">
                                                            <span>{{ $file->title ?? $file->file_name }}</span>
                                                            <button type="button" class="btn btn-sm btn-danger remove-file" data-uuid="{{ $fileUuid }}">Remove</button>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-muted">No files attached</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

{{--
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label">Summary</label>
                                    <textarea name="summary" class="form-control" rows="3"
                                        placeholder="Brief summary of the service...">{{ old('summary', $item->summary) }}</textarea>
                                    <small class="text-muted">A short summary that appears in service listings and previews.</small>
                                    @error('summary')
                                        <small class="text-danger d-block">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
--}}

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
                            <a href="{{ route('settings.services.list') }}" class="btn btn-secondary">
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
            // Dependent Category Dropdown
            const parentCategorySelect = $('#parent_category_id');
            const childCategorySelect = $('#sub_category_ids');
            const selectedChildIds = {!! json_encode(old('sub_category_ids', $selectedSubCategoryIds ?? [])) !!};

            function loadChildCategories(parentId, selectedIds = null) {
                if (!parentId) {
                    childCategorySelect.html('').trigger('change');
                    return;
                }

                $.ajax({
                    url: "{{ route('settings.services.get-categories') }}",
                    type: 'GET',
                    data: { parent_id: parentId },
                    success: function(data) {
                        let html = '';
                        $.each(data, function(id, name) {
                            const selected = (selectedIds && selectedIds.includes(parseInt(id))) ? 'selected' : '';
                            html += `<option value="${id}" ${selected}>${name}</option>`;
                        });
                        childCategorySelect.html(html).trigger('change');
                    },
                    error: function() {
                        childCategorySelect.html('<option value="">Error loading categories</option>').trigger('change');
                    }
                });
            }

            parentCategorySelect.on('change', function() {
                loadChildCategories($(this).val());
            });

            // Initial load for edit mode
            if (parentCategorySelect.val()) {
                loadChildCategories(parentCategorySelect.val(), selectedChildIds);
            }

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

            // Remove thumbnail handler
            $('#remove_thumbnail_btn').on('click', function() {
                $('#thumbnail_id').val('');
                $('#remove_thumbnail').val('1');
                $('#thumbnail_preview').html('<span class="text-muted">No media selected</span>');
            });

            // Remove attached file handler
            $(document).on('click', '.remove-file', function() {
                const uuid = $(this).data('uuid');
                let files = JSON.parse($('#attached_files').val() || '[]');
                files = files.filter(f => f !== uuid);
                $('#attached_files').val(JSON.stringify(files));
                $(this).closest('.list-group-item').remove();

                if (files.length === 0) {
                    $('#attached_files_preview').html('<span class="text-muted">No files attached</span>');
                }
            });

            // Handle media manager callback for multiple files
            $(document).on('media-manager:selected', function(e, data) {
                if (data.target === '#attached_files' && data.mode === 'multiple') {
                    let currentFiles = JSON.parse($('#attached_files').val() || '[]');
                    if (Array.isArray(data.selected)) {
                        // Add new files to existing list (avoid duplicates)
                        data.selected.forEach(function(uuid) {
                            if (currentFiles.indexOf(uuid) === -1) {
                                currentFiles.push(uuid);
                            }
                        });
                    }
                    $('#attached_files').val(JSON.stringify(currentFiles));
                }
            });
        });
    </script>
@endpush
