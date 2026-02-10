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
                        action="{{ $item->exists ? route('settings.pages.update', $item->uuid) : route('settings.pages.store') }}">
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
                                    <label class="form-label">Website Section</label>
                                    <select name="website_section_id" id="website_section_id" class="form-control select2">
                                        <option value="">Select Website Section</option>
                                        @foreach ($websiteSections ?? [] as $section)
                                            <option value="{{ $section->id }}"
                                                {{ old('website_section_id', $item->website_section_id) == $section->id ? 'selected' : '' }}>
                                                {{ $section->heading ?? $section->title }}{{ $section->subheading ? ' - ' . $section->subheading : '' }} ({{ $section->section_type }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('website_section_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4" id="category_wrapper" style="display: none;">
                                <div class="form-group">
                                    <label class="form-label">Category</label>
                                    <select name="category_id" id="category_id" class="form-control select2">
                                        <option value="">Select Category</option>
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
                                        @foreach (['draft', 'scheduled', 'published', 'archived'] as $status)
                                            <option value="{{ $status }}"
                                                {{ old('status', $item->status ?? 'published') === $status ? 'selected' : '' }}>
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

                        <hr>

                        {{-- Page Detail Configuration - Collapsible Section --}}
                        <div class="card mb-3">
                            <div class="card-header" style="cursor: pointer;" data-bs-toggle="collapse" data-bs-target="#pageDetailConfig" aria-expanded="false" aria-controls="pageDetailConfig">
                                <h6 class="mb-0 d-flex justify-content-between align-items-center">
                                    <span>Page Detail Configuration</span>
                                    <i class="bx bx-chevron-down" id="pageDetailConfigIcon"></i>
                                </h6>
                            </div>
                            <div class="collapse" id="pageDetailConfig">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label">Display Module Template Type</label>
                                                <select name="display_module_type" id="display_module_type" class="form-control">
                                                    <option value="">None</option>
                                                    <option value="jobs" {{ old('display_module_type', $item->display_module_type) == 'jobs' ? 'selected' : '' }}>Jobs</option>
                                                    <option value="services" {{ old('display_module_type', $item->display_module_type) == 'services' ? 'selected' : '' }}>Services</option>
                                                    <option value="tenders" {{ old('display_module_type', $item->display_module_type) == 'tenders' ? 'selected' : '' }}>Tenders</option>
                                                    <option value="rules_and_regulations" {{ old('display_module_type', $item->display_module_type) == 'rules_and_regulations' ? 'selected' : '' }}>Rules and Regulations</option>
                                                    <option value="downloads" {{ old('display_module_type', $item->display_module_type) == 'downloads' ? 'selected' : '' }}>Downloads</option>
                                                    <option value="feedback" {{ old('display_module_type', $item->display_module_type) == 'feedback' ? 'selected' : '' }}>Feedback Form</option>
                                                    <option value="faqs" {{ old('display_module_type', $item->display_module_type) == 'faqs' ? 'selected' : '' }}>Faqs</option>
                                                    <option value="footer" {{ old('display_module_type', $item->display_module_type) == 'footer' ? 'selected' : '' }}>Footer</option>
                                                    <option value="our_heroes" {{ old('display_module_type', $item->display_module_type) == 'our_heroes' ? 'selected' : '' }}>Our Heroes</option>
                                                </select>
                                                <small class="text-muted">Select what type of content to display on this page detail</small>
                                                @error('display_module_type')
                                                    <small class="text-danger d-block">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group" id="template-selector" style="display: {{ old('display_module_type', $item->display_module_type) ? 'block' : 'none' }};">
                                                <label class="form-label">Detail Template</label>
                                                <select name="detail_template" class="form-control" id="detail_template">
                                                    <option value="">Select Template</option>
                                                    @if(old('display_module_type', $item->display_module_type))
                                                        @php
                                                            $templates = \App\Enums\PageDetailTemplates\PageDetailTemplate::optionsForModule(old('display_module_type', $item->display_module_type));
                                                        @endphp
                                                        @foreach($templates as $template)
                                                            <option value="{{ $template['value'] }}"
                                                                {{ old('detail_template', $item->detail_template?->value) == $template['value'] ? 'selected' : '' }}>
                                                                {{ $template['label'] }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                <small class="text-muted">Select template for displaying items on detail page</small>
                                                @error('detail_template')
                                                    <small class="text-danger d-block">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label">External URL</label>
                                                <input type="url" name="external_url" class="form-control"
                                                    value="{{ old('external_url', $item->external_url) }}"
                                                    placeholder="https://example.com">
                                                <small class="text-muted">If set, page will redirect to this URL or embed it as iframe</small>
                                                @error('external_url')
                                                    <small class="text-danger d-block">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label">Show in Iframe</label>
                                                <select name="iframe" class="form-control">
                                                    <option value="0" {{ old('iframe', $item->iframe ? 1 : 0) == 0 ? 'selected' : '' }}>Disabled</option>
                                                    <option value="1" {{ old('iframe', $item->iframe ? 1 : 0) == 1 ? 'selected' : '' }}>Enabled</option>
                                                </select>
                                                <small class="text-muted">Enable to embed external URL as iframe instead of redirecting</small>
                                                @error('iframe')
                                                    <small class="text-danger d-block">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label req">Media Type</label>
                                    <select name="media_type" class="form-control" id="media_type" required>
                                        @foreach (['none' => 'None', 'image' => 'Image', 'video' => 'Video'] as $key => $label)
                                            <option value="{{ $key }}"
                                                {{ old('media_type', $item->media_type ?? 'image') === $key ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('media_type')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-4" id="featured_image_field" style="display: none;">
                                <div class="form-group">
                                    <label class="form-label d-flex justify-content-between align-items-center">
                                        <span>Featured Image</span>
                                        @if($item->exists && $item->featuredImage)
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-danger"
                                                    id="remove_featured_image_btn">
                                                Remove
                                            </button>
                                        @endif
                                    </label>
                                    @error('featured_image_id')
                                    <span class="text-danger d-block">{{ $message }}</span>
                                    @enderror
                                    {{-- hidden field to store selected media UUID --}}
                                    <input type="hidden" name="featured_image_id" id="featured_image_id" value="{{ old('featured_image_id', $item->featuredImage->uuid ?? '') }}">
                                    {{-- flag to remove existing featured image --}}
                                    <input type="hidden" name="remove_featured_image" id="remove_featured_image" value="0">
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
                            <div class="col-md-4" id="video_url_field" style="display: none;">
                                <div class="form-group">
                                    <label class="form-label">Video URL</label>
                                    <input type="url" name="video_url" class="form-control"
                                        value="{{ old('video_url', $item->video_url) }}">
                                    @error('video_url')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4" id="video_thumbnail_field" style="display: none;">
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
                            <div class="col-md-4">
                                <label class="form-label">Meta Title</label>
                                <input type="text" name="meta_title" class="form-control"
                                    value="{{ old('meta_title', $item->meta['title'] ?? '') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Meta Keywords</label>
                                <input type="text" name="meta_keywords" class="form-control"
                                    value="{{ old('meta_keywords', $item->meta['keywords'] ?? '') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Meta Description</label>
                                <textarea name="meta_description" class="form-control" rows="2">{{ old('meta_description', $item->meta['description'] ?? '') }}</textarea>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="mb-0">Related Links</h6>
                                    <button type="button" class="btn btn-sm btn-primary" id="add-related-link">
                                        <i class="bx bx-plus"></i> Add More
                                    </button>
                                </div>
                                <div id="related-links-container">
                                    @php
                                        $relatedLinks = old('related_links', $item->relatedLinks ?? []);
                                        if (empty($relatedLinks)) {
                                            $relatedLinks = [['title' => '', 'url' => '', 'sort_order' => 0]];
                                        }
                                    @endphp
                                    @foreach($relatedLinks as $index => $link)
                                        <div class="related-link-item border p-3 mb-3" data-index="{{ $index }}">
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <label class="form-label">Link Title</label>
                                                    <input type="text" name="related_links[{{ $index }}][title]"
                                                        class="form-control"
                                                        value="{{ old("related_links.{$index}.title", $link['title'] ?? '') }}"
                                                        placeholder="Enter link title">
                                                    @error("related_links.{$index}.title")
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                                <div class="col-md-5">
                                                    <label class="form-label">Link URL</label>
                                                    <input type="url" name="related_links[{{ $index }}][url]"
                                                        class="form-control"
                                                        value="{{ old("related_links.{$index}.url", $link['url'] ?? '') }}"
                                                        placeholder="https://example.com">
                                                    @error("related_links.{$index}.url")
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                                <div class="col-md-1">
                                                    <label class="form-label">Order</label>
                                                    <input type="number" name="related_links[{{ $index }}][sort_order]"
                                                        class="form-control"
                                                        value="{{ old("related_links.{$index}.sort_order", $link['sort_order'] ?? $index) }}"
                                                        min="0">
                                                </div>
                                                <div class="col-md-1 d-flex align-items-end">
                                                    <button type="button" class="btn btn-sm btn-danger remove-related-link"
                                                        @if(count($relatedLinks) == 1) style="display:none;" @endif>
                                                        <i class="bx bx-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <a href="{{ route('settings.pages.list') }}" class="btn btn-secondary">
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

                        // Image alignment CSS
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
            $('#featured_image_id').on('change', function() {
                if ($(this).val()) {
                    $('#remove_featured_image').val('0');
                }
            });

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

            // Media type field visibility control
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
                } else if (mediaType === 'none') {
                    // When switching to "none", clear featured image so it doesn't persist
                    $('#featured_image_id').val('');
                    $('#remove_featured_image').val('1');
                    $('#featured_image_preview').html('<span class="text-muted">No media selected</span>');
                }
            }

            // Template selector based on module type
            $('#display_module_type').on('change', function() {
                const moduleType = $(this).val();
                const $templateSelect = $('#detail_template');
                const $templateSelector = $('#template-selector');

                if (moduleType) {
                    $templateSelector.show();
                    $templateSelect.empty().append('<option value="">Loading...</option>');

                    $.ajax({
                        url: '{{ route('settings.pages.templates') }}',
                        type: 'GET',
                        data: { module_type: moduleType },
                        success: function(templates) {
                            $templateSelect.empty().append('<option value="">Select Template</option>');
                            templates.forEach(function(template) {
                                $templateSelect.append(
                                    $('<option></option>')
                                        .attr('value', template.value)
                                        .text(template.label)
                                );
                            });
                            // Re-select old value if available
                            const oldTemplate = '{{ old('detail_template', $item->detail_template?->value) }}';
                            if (oldTemplate) {
                                $templateSelect.val(oldTemplate).trigger('change');
                            }
                        },
                        error: function() {
                            $templateSelect.empty().append('<option value="">Error loading templates</option>');
                        }
                    });
                } else {
                    $templateSelector.hide();
                    $templateSelect.empty();
                }
            }).trigger('change'); // Trigger on load for edit mode

            // Initialize on page load
            toggleMediaFields();

            // Update on change
            $('#media_type').on('change', function() {
                toggleMediaFields();
            });

            // Remove featured image handler (edit case)
            $('#remove_featured_image_btn').on('click', function () {
                $('#featured_image_id').val('');
                $('#remove_featured_image').val('1');
                $('#featured_image_preview').html('<span class="text-muted">No media selected</span>');
            });

            // Load website sections when company changes
            function loadWebsiteSections(companyId, selectedSectionId) {
                const $sectionSelect = $('#website_section_id');

                if (!companyId) {
                    $sectionSelect.empty().append('<option value="">Select Website Section</option>');
                    if ($sectionSelect.hasClass('select2-hidden-accessible')) {
                        $sectionSelect.trigger('change');
                    }
                    return;
                }

                // Show loading state
                $sectionSelect.prop('disabled', true);

                $.ajax({
                    url: '{{ route("settings.pages.website-sections") }}',
                    type: 'GET',
                    data: { company_id: companyId },
                    success: function(data) {
                        $sectionSelect.empty().append('<option value="">Select Website Section</option>');

                        if (data && data.length > 0) {
                            data.forEach(function(section) {
                                const isSelected = selectedSectionId && section.id == selectedSectionId ? 'selected' : '';
                                // Build label: heading (or title) - subheading (section_type)
                                const heading = section.heading || section.title || '';
                                const subheading = section.subheading ? ' - ' + section.subheading : '';
                                const sectionType = section.section_type ? ' (' + section.section_type + ')' : '';
                                const label = heading + subheading + sectionType;
                                $sectionSelect.append('<option value="' + section.id + '" ' + isSelected + '>' + label + '</option>');
                            });
                        }

                        // Reinitialize select2 if needed
                        if ($sectionSelect.hasClass('select2-hidden-accessible')) {
                            $sectionSelect.trigger('change');
                        }
                    },
                    error: function() {
                        console.error('Failed to load website sections');
                    },
                    complete: function() {
                        $sectionSelect.prop('disabled', false);
                    }
                });
            }

            // Load categories based on website section
            function loadCategories(websiteSectionId, selectedCategoryId) {
                const $categorySelect = $('#category_id');
                const $categoryWrapper = $('#category_wrapper');

                $categorySelect.empty().append('<option value="">Select Category</option>');

                if (!websiteSectionId) {
                    $categoryWrapper.hide();
                    if ($categorySelect.hasClass('select2-hidden-accessible')) {
                        $categorySelect.trigger('change');
                    }
                    return;
                }

                $categoryWrapper.show();
                $categorySelect.prop('disabled', true);

                $.ajax({
                    url: '{{ route("settings.pages.categories") }}',
                    type: 'GET',
                    data: { website_section_id: websiteSectionId },
                    success: function(data) {
                        if (data) {
                            $.each(data, function(id, name) {
                                const isSelected = selectedCategoryId && id == selectedCategoryId ? 'selected' : '';
                                $categorySelect.append('<option value="' + id + '" ' + isSelected + '>' + name + '</option>');
                            });
                        }

                        if ($categorySelect.hasClass('select2-hidden-accessible')) {
                            $categorySelect.trigger('change');
                        }
                    },
                    error: function() {
                        console.error('Failed to load categories');
                    },
                    complete: function() {
                        $categorySelect.prop('disabled', false);
                    }
                });
            }

            // Bind change event to company select
            $('#company_id').on('change', function() {
                loadWebsiteSections($(this).val(), null);
                // Reset categories when company changes
                loadCategories(null, null);
            });

            // Initialize sections on page load
            const initialCompanyId = $('#company_id').val();
            const initialSectionId = '{{ old("website_section_id", $item->website_section_id ?? "") }}';
            const initialCategoryId = '{{ old("category_id", $item->category_id ?? "") }}';

            // Load categories when website section changes
            $('#website_section_id').on('change', function() {
                const sectionId = $(this).val();
                loadCategories(sectionId, null);
            });

            if (initialCompanyId) {
                loadWebsiteSections(initialCompanyId, initialSectionId);
            }

            // Load categories for initial section (edit mode)
            if (initialSectionId) {
                loadCategories(initialSectionId, initialCategoryId);
            }

            // Collapsible section icon rotation
            $('#pageDetailConfig').on('show.bs.collapse', function () {
                $('#pageDetailConfigIcon').removeClass('bx-chevron-down').addClass('bx-chevron-up');
            }).on('hide.bs.collapse', function () {
                $('#pageDetailConfigIcon').removeClass('bx-chevron-up').addClass('bx-chevron-down');
            });

            // Related Links functionality
            let relatedLinkIndex = {{ count(old('related_links', $item->relatedLinks ?? [])) }};

            // Add new related link
            $('#add-related-link').on('click', function() {
                const newItem = `
                    <div class="related-link-item border p-3 mb-3" data-index="${relatedLinkIndex}">
                        <div class="row">
                            <div class="col-md-5">
                                <label class="form-label">Link Title</label>
                                <input type="text" name="related_links[${relatedLinkIndex}][title]"
                                    class="form-control"
                                    placeholder="Enter link title">
                            </div>
                            <div class="col-md-5">
                                <label class="form-label">Link URL</label>
                                <input type="url" name="related_links[${relatedLinkIndex}][url]"
                                    class="form-control"
                                    placeholder="https://example.com">
                            </div>
                            <div class="col-md-1">
                                <label class="form-label">Order</label>
                                <input type="number" name="related_links[${relatedLinkIndex}][sort_order]"
                                    class="form-control"
                                    value="${relatedLinkIndex}"
                                    min="0">
                            </div>
                            <div class="col-md-1 d-flex align-items-end">
                                <button type="button" class="btn btn-sm btn-danger remove-related-link">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                $('#related-links-container').append(newItem);
                relatedLinkIndex++;
                updateRemoveButtons();
            });

            // Remove related link
            $(document).on('click', '.remove-related-link', function() {
                $(this).closest('.related-link-item').remove();
                updateRemoveButtons();
            });

            // Update remove buttons visibility
            function updateRemoveButtons() {
                const items = $('.related-link-item');
                if (items.length <= 1) {
                    $('.remove-related-link').hide();
                } else {
                    $('.remove-related-link').show();
                }
            }

            // Initialize remove buttons visibility
            updateRemoveButtons();
        });
    </script>

@endpush
