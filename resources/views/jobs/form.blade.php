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
                        action="{{ $item->exists ? route('settings.jobs.update', $item->uuid) : route('settings.jobs.store') }}">
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

                        <div class="row">
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
                                    <label class="form-label req">Job Type</label>
                                    <select name="job_type" class="form-control" required>
                                        @foreach (['regular' => 'Regular', 'contractual' => 'Contractual', 'consultant' => 'Consultant'] as $key => $label)
                                            <option value="{{ $key }}"
                                                {{ old('job_type', $item->job_type ?? 'regular') === $key ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('job_type')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Scale</label>
                                    <input type="text" name="scale" class="form-control"
                                        value="{{ old('scale', $item->scale) }}">
                                    @error('scale')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Vacancies</label>
                                    <input type="number" name="vacancies" class="form-control" min="1"
                                        value="{{ old('vacancies', $item->vacancies ?? 1) }}">
                                    @error('vacancies')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Expiry Date</label>
                                    <input type="datetime-local" name="expiry_date" class="form-control"
                                        value="{{ old('expiry_date', optional($item->expiry_date)->format('Y-m-d\TH:i')) }}">
                                    @error('expiry_date')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
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
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Experience</label>
                                    <input type="text" name="experience" class="form-control"
                                        value="{{ old('experience', $item->experience) }}">
                                    @error('experience')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Age Limit</label>
                                    <input type="text" name="age_limit" class="form-control"
                                        value="{{ old('age_limit', $item->age_limit) }}">
                                    @error('age_limit')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Experience Field</label>
                                    <input type="text" name="experience_field" class="form-control"
                                        value="{{ old('experience_field', $item->experience_field) }}">
                                    @error('experience_field')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
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

                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label class="form-label mb-0 req">Body</label>
                                    <button type="button" class="btn btn-primary btn-sm open-media-manager"
                                        data-mode="ckeditor"
                                        data-ckeditor-instance="body-editor"
                                        data-company-select="#company_id">
                                        <i class="bx bx-image"></i> Insert Images
                                    </button>
                                </div>
                                <textarea name="body" id="body-editor" class="form-control" rows="10" required>{{ old('body', $item->body) }}</textarea>
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
                            <a href="{{ route('settings.jobs.list') }}" class="btn btn-secondary">
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
            function initCKEditor() {
                if (typeof CKEDITOR !== 'undefined' && $('#body-editor').length) {
                    CKEDITOR.replace('body-editor', {
                        height: 500,
                        extraAllowedContent: '*[*]{*}(*)',
                        allowedContent: true,
                        
                        // Enable all available plugins
                        extraPlugins: 'tableresize,tabletools,tableselection,colorbutton,colordialog,font,justify,iframe,codesnippet,emoji,autoembed,embedsemantic,autolink,showblocks,div,find,horizontalrule,pagebreak,preview,print,save,selectall,smiley,specialchar,stylescombo',
                        removePlugins: 'exportpdf',
                        
                        contentsCss: [
                            'https://cdn.ckeditor.com/4.22.1/full-all/contents.css',
                            'data:text/css,' + encodeURIComponent(`
                                img.align-left { float: left !important; margin: 5px 15px 5px 0 !important; }
                                img.align-right { float: right !important; margin: 5px 0 5px 15px !important; }
                                img.align-center { display: block !important; margin: 10px auto !important; }
                            `)
                        ],
                        
                        // Table settings
                        table_defaultCellPadding: 5,
                        table_defaultCellSpacing: 0,
                        table_defaultCellBorder: 1,
                        
                        // Code snippet settings
                        codeSnippet_theme: 'monokai_sublime',
                        codeSnippet_languages: {
                            javascript: 'JavaScript', php: 'PHP', python: 'Python', java: 'Java',
                            css: 'CSS', html: 'HTML', sql: 'SQL', json: 'JSON', xml: 'XML',
                            bash: 'Bash', typescript: 'TypeScript', csharp: 'C#', cpp: 'C++'
                        },
                        
                        // Toolbar layout
                        toolbarGroups: [
                            { name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
                            { name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
                            { name: 'editing', groups: [ 'find', 'selection', 'editing' ] },
                            '/',
                            { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
                            { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'paragraph' ] },
                            { name: 'links', groups: [ 'links' ] },
                            { name: 'insert', groups: [ 'insert' ] },
                            '/',
                            { name: 'styles', groups: [ 'styles' ] },
                            { name: 'colors', groups: [ 'colors' ] },
                            { name: 'tools', groups: [ 'tools' ] }
                        ],
                        
                        removeButtons: 'About,Language,BidiLtr,BidiRtl,Flash,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField',
                        
                        // Format and font options
                        format_tags: 'p;h1;h2;h3;h4;h5;h6;pre;address;div',
                        font_names: 'Arial;Comic Sans MS;Courier New;Georgia;Lucida Sans Unicode;Tahoma;Times New Roman;Trebuchet MS;Verdana',
                        fontSize_sizes: '8/8px;10/10px;12/12px;14/14px;16/16px;18/18px;20/20px;24/24px;28/28px;36/36px;48/48px;72/72px',
                        
                        // Colors
                        colorButton_colors: 'CF5D4E,454545,FFF,DDD,CCEAEE,66AB16,000,1ABC9C,2ECC71,3498DB,9B59B6,4E5F70,F1C40F,16A085,27AE60,2980B9,8E44AD,2C3E50,F39C12,E67E22,E74C3C,ECF0F1,95A5A6',
                        colorButton_enableMore: true,
                        
                        // Auto-embed YouTube, Twitter, etc.
                        autoEmbed_widget: 'embedSemantic',
                        
                        // Context menu
                        contextmenu: 'link,image,table,tableproperties,tabledelete,tablerow,tablecolumn,tablecell',
                        
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

                    // Update preview
                    if (currentFiles.length > 0) {
                        // Reload preview via AJAX or rebuild from currentFiles
                        // For now, just update the hidden field - preview will refresh on page reload
                    }
                }
            });
        });
    </script>
@endpush
