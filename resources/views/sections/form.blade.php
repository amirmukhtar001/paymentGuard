@extends('layouts.' . config('settings.active_layout'))

@push('styles')
    <style>
        .form-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .form-section-title {
            font-size: 16px;
            font-weight: 600;
            color: #333;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #dee2e6;
        }

        .help {
            display: block;
            color: #dc3545;
            font-size: 12px;
            margin-top: 5px;
        }

        .req::after {
            content: " *";
            color: #dc3545;
        }

        .json-editor {
            font-family: 'Courier New', monospace;
            font-size: 13px;
        }

        .info-box {
            background: #e7f3ff;
            border-left: 4px solid #2196F3;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }

        .info-box i {
            color: #2196F3;
            margin-right: 8px;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function () {
            // Auto-generate slug from name
            $('#name').on('input', function () {
                if (!$('#slug').val() || $('#slug').data('auto')) {
                    let slug = $(this).val()
                        .toLowerCase()
                        .replace(/[^a-z0-9]+/g, '-')
                        .replace(/^-+|-+$/g, '');
                    $('#slug').val(slug).data('auto', true);
                }
            });

            $('#slug').on('input', function () {
                $(this).data('auto', false);
            });

            // Auto-generate section key
            $('#page_type, #slug').on('change input', function () {
                if (!$('#section_key').val() || $('#section_key').data('auto')) {
                    let pageType = $('#page_type').val();
                    let slug = $('#slug').val();
                    if (pageType && slug) {
                        $('#section_key').val(pageType + '_' + slug).data('auto', true);
                    }
                }
            });

            $('#section_key').on('input', function () {
                $(this).data('auto', false);
            });

            // Validate JSON
            $('#settings').on('blur', function () {
                let value = $(this).val().trim();
                if (value) {
                    try {
                        JSON.parse(value);
                        $(this).removeClass('is-invalid').addClass('is-valid');
                        $('#json-error').hide();
                    } catch (e) {
                        $(this).removeClass('is-valid').addClass('is-invalid');
                        $('#json-error').text('Invalid JSON: ' + e.message).show();
                    }
                } else {
                    $(this).removeClass('is-invalid is-valid');
                    $('#json-error').hide();
                }
            });

            // Format JSON on load
            let settingsField = $('#settings');
            if (settingsField.val()) {
                try {
                    let formatted = JSON.stringify(JSON.parse(settingsField.val()), null, 2);
                    settingsField.val(formatted);
                } catch (e) {
                    // Keep original value if not valid JSON
                }
            }
        });
    </script>
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">{{ $title }}</h5>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ $item->exists
        ? route('sections.update', $item->uuid)
        : route('sections.store') }}">
                        @csrf
                        @if($item->exists)
                            @method('PUT')
                        @endif

                        <!-- Basic Information -->
                        <div class="form-section">
                            <div class="form-section-title">
                                <i class="bx bx-info-circle"></i> Basic Information
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="name" class="form-label req">Section Name</label>
                                        <span class="help">
                                            @if(session()->has('errors'))
                                                {!! session()->get('errors')->first('name') !!}
                                            @endif
                                        </span>
                                        <input type="text" name="name" id="name" class="form-control"
                                            value="{{ old('name', $item->name ?? '') }}" placeholder="e.g., Gallery Section"
                                            required>
                                        <small class="text-muted">Internal name for this section</small>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="page_type" class="form-label req">Page Type</label>
                                        <span class="help">
                                            @if(session()->has('errors'))
                                                {!! session()->get('errors')->first('page_type') !!}
                                            @endif
                                        </span>
                                        <select name="page_type" id="page_type" class="form-control" required>
                                            <option value="">Select Page Type</option>
                                            <option value="home" {{ old('page_type', $item->page_type ?? '') === 'home' ? 'selected' : '' }}>Home</option>
                                            <option value="about" {{ old('page_type', $item->page_type ?? '') === 'about' ? 'selected' : '' }}>About</option>
                                            <option value="contact" {{ old('page_type', $item->page_type ?? '') === 'contact' ? 'selected' : '' }}>Contact</option>
                                            <option value="custom" {{ old('page_type', $item->page_type ?? '') === 'custom' ? 'selected' : '' }}>Custom</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="slug" class="form-label">Slug</label>
                                        <span class="help">
                                            @if(session()->has('errors'))
                                                {!! session()->get('errors')->first('slug') !!}
                                            @endif
                                        </span>
                                        <input type="text" name="slug" id="slug" class="form-control"
                                            value="{{ old('slug', $item->slug ?? '') }}" placeholder="gallery-section">
                                        <small class="text-muted">Auto-generated from name</small>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="section_key" class="form-label">Section Key</label>
                                        <span class="help">
                                            @if(session()->has('errors'))
                                                {!! session()->get('errors')->first('section_key') !!}
                                            @endif
                                        </span>
                                        <input type="text" name="section_key" id="section_key" class="form-control"
                                            value="{{ old('section_key', $item->section_key ?? '') }}"
                                            placeholder="home_gallery_section">
                                        <small class="text-muted">Unique identifier for this section</small>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="title" class="form-label">Display Title</label>
                                        <span class="help">
                                            @if(session()->has('errors'))
                                                {!! session()->get('errors')->first('title') !!}
                                            @endif
                                        </span>
                                        <input type="text" name="title" id="title" class="form-control"
                                            value="{{ old('title', $item->title ?? '') }}" placeholder="Our Gallery">
                                        <small class="text-muted">Title shown on frontend</small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <span class="help">
                                            @if(session()->has('errors'))
                                                {!! session()->get('errors')->first('description') !!}
                                            @endif
                                        </span>
                                        <textarea name="description" id="description" class="form-control" rows="3"
                                            placeholder="Brief description of this section">{{ old('description', $item->description ?? '') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Display Settings -->
                        <div class="form-section">
                            <div class="form-section-title">
                                <i class="bx bx-cog"></i> Display Settings
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group mb-3">
                                        <label for="layout_type" class="form-label req">Layout Type</label>
                                        <span class="help">
                                            @if(session()->has('errors'))
                                                {!! session()->get('errors')->first('layout_type') !!}
                                            @endif
                                        </span>
                                        <select name="layout_type" id="layout_type" class="form-control" required>
                                            <option value="grid" {{ old('layout_type', $item->layout_type ?? 'grid') === 'grid' ? 'selected' : '' }}>Grid</option>
                                            <option value="list" {{ old('layout_type', $item->layout_type ?? '') === 'list' ? 'selected' : '' }}>List</option>
                                            <option value="slider" {{ old('layout_type', $item->layout_type ?? '') === 'slider' ? 'selected' : '' }}>Slider</option>
                                            <option value="masonry" {{ old('layout_type', $item->layout_type ?? '') === 'masonry' ? 'selected' : '' }}>Masonry</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group mb-3">
                                        <label for="items_limit" class="form-label">Items Limit</label>
                                        <span class="help">
                                            @if(session()->has('errors'))
                                                {!! session()->get('errors')->first('items_limit') !!}
                                            @endif
                                        </span>
                                        <input type="number" name="items_limit" id="items_limit" class="form-control"
                                            value="{{ old('items_limit', $item->items_limit ?? 6) }}" min="1" max="50">
                                        <small class="text-muted">Max items to display</small>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group mb-3">
                                        <label for="display_order" class="form-label">Display Order</label>
                                        <span class="help">
                                            @if(session()->has('errors'))
                                                {!! session()->get('errors')->first('display_order') !!}
                                            @endif
                                        </span>
                                        <input type="number" name="display_order" id="display_order" class="form-control"
                                            value="{{ old('display_order', $item->display_order ?? 0) }}" min="0">
                                        <small class="text-muted">Lower numbers appear first</small>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group mb-3">
                                        <label for="status" class="form-label req">Status</label>
                                        <span class="help">
                                            @if(session()->has('errors'))
                                                {!! session()->get('errors')->first('status') !!}
                                            @endif
                                        </span>
                                        <select name="status" id="status" class="form-control" required>
                                            <option value="active" {{ old('status', $item->status ?? 'active') === 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="inactive" {{ old('status', $item->status ?? '') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Settings -->
                        <div class="form-section">
                            <div class="form-section-title">
                                <i class="bx bx-code-block"></i> Additional Settings (Optional)
                            </div>

                            <div class="info-box">
                                <i class="bx bx-info-circle"></i>
                                <strong>JSON Settings:</strong> You can add custom settings in JSON format.
                                Example: <code>{"columns": 3, "show_title": true, "autoplay": false}</code>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group mb-3">
                                        <label for="settings" class="form-label">Settings (JSON)</label>
                                        <span class="help" id="json-error" style="display: none;"></span>
                                        <span class="help">
                                            @if(session()->has('errors'))
                                                {!! session()->get('errors')->first('settings') !!}
                                            @endif
                                        </span>
                                        <textarea name="settings" id="settings" class="form-control json-editor" rows="6"
                                            placeholder='&#123;
      "columns": 3,
      "show_title": true,
      "show_description": true,
      "autoplay": false
    &#125;'>{{ old('settings', $item->settings ? json_encode($item->settings, JSON_PRETTY_PRINT) : '') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="row">
                            <div class="col-12">
                                <a href="{{ route('sections.list') }}" class="btn btn-warning">
                                    <i class="bx bx-arrow-back"></i> Back
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="bx bx-save"></i> Save Section
                                </button>
                                @if($item->exists)
                                    <a href="{{ route('sections.manage-items', $item->uuid) }}" class="btn btn-info">
                                        <i class="bx bx-list-ul"></i> Manage Items
                                    </a>
                                @endif
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection