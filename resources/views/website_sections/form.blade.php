@extends('layouts.' . config('settings.active_layout'))

{{-- ✅ Global media manager (WordPress-style) --}}
@include('components.media-manager', ['companies' => $companies])

@push('stylesheets')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/pickr/pickr-themes.css') }}">
<style>
    .color-picker-group .pickr-trigger {
        border: 1px solid var(--bs-border-color, #d9dee3);
        border-radius: 0.375rem;
        padding: 0.45rem 0.75rem;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        background-color: #fff;
    }

    .color-picker-group .color-dot {
        width: 1.75rem;
        height: 1.75rem;
        border-radius: 999px;
        border: 1px solid rgba(0, 0, 0, 0.1);
        display: inline-block;
    }

    .color-picker-group .color-value {
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.85rem;
    }

    .input-group .pickr-trigger {
        border-left: 0;
        padding: 0.375rem 0.75rem;
    }

    .input-group .pickr-trigger.align-self-start {
        align-self: flex-start;
        margin-top: 0;
    }

    .input-group textarea + .pickr-trigger {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
        height: fit-content;
    }

    .input-group .pickr-trigger .color-dot {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        border: 1px solid rgba(0, 0, 0, 0.1);
        display: inline-block;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header header-elements-inline">
                <h5 class="card-title">{{ $title }}</h5>
                <div class="header-elements">
                    <a href="{{ route('settings.website-sections.list') }}" class="btn btn-warning">
                        <i class="bx bx-arrow-back"></i> Back
                    </a>
                </div>
            </div>

            <div class="card-body">
                        <form method="POST"
                      action="{{ $item->exists
                            ? route('settings.website-sections.update', ['website_section' => $item->uuid])
                            : route('settings.website-sections.store') }}">
                    @csrf
                    @if($item->exists)
                        @method('PUT')
                    @endif

                    <div class="row">
                        <div class="col-lg-3">
                            <!-- Sidebar card for quick settings -->
                            <div class="card mb-3 shadow-none border">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Visibility & Status</h6>
                                </div>
                                <div class="card-body p-3">
                                    <div class="form-group mb-0">
                                        <label class="form-label req">Status</label>
                                        <select name="status" class="form-control" required>
                                            @foreach(['draft' => 'Draft', 'active' => 'Active', 'hidden' => 'Hidden', 'archived' => 'Archived'] as $value => $label)
                                                <option value="{{ $value }}" {{ old('status', $item->status ?? 'active') === $value ? 'selected' : '' }}>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-9">
                            <ul class="nav nav-tabs mb-3" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#tab-basic" role="tab">Basic Info</a>
                                </li>
                            </ul>

                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="tab-basic" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mt-3">
                                                <label class="form-label req">Website</label>
                                                <select name="company_id" class="form-control select2" required>
                                                    <option value="">Select Website</option>
                                                    @foreach($companies as $id => $name)
                                                        <option value="{{ $id }}" {{ old('company_id', $item->company_id ?? '') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mt-3">
                                                <label class="form-label">Module Type</label>
                                                <select name="section_type" class="form-control">
                                                    <option value="">Select Module Type</option>
                                                    @foreach($sectionTypes as $key => $label)
                                                        <option value="{{ $key }}" {{ old('section_type', $item->section_type ?? '') === $key ? 'selected' : '' }}>{{ $label }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mt-3">
                                                <label class="form-label req">Heading</label>
                                                <div class="input-group">
                                                    <input type="text" name="heading" id="heading" class="form-control" value="{{ old('heading', $item->heading ?? '') }}" required>
                                                    <input type="hidden" name="title" id="title" value="{{ old('title', $item->title ?? '') }}">
                                                    @php
                                                        $headingColor = strtoupper(old('heading_color', $item->heading_color ?? ''));
                                                        if ($headingColor === '' || !preg_match('/^#([0-9a-f]{6})$/i', $headingColor)) {
                                                            $headingColor = '#000000';
                                                        }
                                                    @endphp
                                                    <button type="button"
                                                            class="btn btn-outline-secondary pickr-trigger"
                                                            data-color-target="color-input-heading_color"
                                                            data-default-color="#000000"
                                                            style="min-width: 50px; border-left: 0;">
                                                        <span class="color-dot" style="background: {{ $headingColor }}; width: 20px; height: 20px; border-radius: 50%; display: inline-block; border: 1px solid rgba(0,0,0,0.1);"></span>
                                                    </button>
                                                    <input type="hidden" name="heading_color" id="color-input-heading_color" value="{{ $headingColor }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mt-3">
                                                <label class="form-label">Subheading</label>
                                                <div class="input-group">
                                                    <input type="text" name="subheading" class="form-control" value="{{ old('subheading', $item->subheading ?? '') }}">
                                                    @php
                                                        $subheadingColor = strtoupper(old('subheading_color', $item->subheading_color ?? ''));
                                                        if ($subheadingColor === '' || !preg_match('/^#([0-9a-f]{6})$/i', $subheadingColor)) {
                                                            $subheadingColor = '#000000';
                                                        }
                                                    @endphp
                                                    <button type="button"
                                                            class="btn btn-outline-secondary pickr-trigger"
                                                            data-color-target="color-input-subheading_color"
                                                            data-default-color="#000000"
                                                            style="min-width: 50px; border-left: 0;">
                                                        <span class="color-dot" style="background: {{ $subheadingColor }}; width: 20px; height: 20px; border-radius: 50%; display: inline-block; border: 1px solid rgba(0,0,0,0.1);"></span>
                                                    </button>
                                                    <input type="hidden" name="subheading_color" id="color-input-subheading_color" value="{{ $subheadingColor }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mt-3">
                                                <label class="form-label">Slug</label>
                                                <input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug', $item->slug ?? '') }}" placeholder="auto-generated if blank">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mt-3">
                                                <label class="form-label">Short Code</label>
                                                <input type="text" name="short_code" class="form-control" value="{{ old('short_code', $item->short_code ?? '') }}" placeholder="Optional short code">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mt-3">
                                        <label class="form-label d-flex justify-content-between align-items-center">
                                            <span>Background Image</span>
                                            @if($item->exists && $item->backgroundImage)
                                                <button type="button"
                                                        class="btn btn-sm btn-outline-danger"
                                                        id="remove_background_image_btn">
                                                    Remove
                                                </button>
                                            @endif
                                        </label>
                                        @error('background_image_id')
                                        <span class="text-danger d-block">{{ $message }}</span>
                                        @enderror
                                        {{-- hidden field to store selected media UUID --}}
                                        <input type="hidden" name="background_image_id" id="background_image_id" value="{{ old('background_image_id', optional($item->backgroundImage)->uuid ?? '') }}">
                                        {{-- flag to remove existing background image --}}
                                        <input type="hidden" name="remove_background_image" id="remove_background_image" value="0">
                                        <button type="button"
                                            class="btn btn-outline-primary open-media-manager"
                                            data-mode="single"data-target-input="#background_image_id" data-preview-target="#background_image_preview" data-company-select="select[name='company_id']"> Choose Media </button>

                                        {{-- Preview --}}
                                        <div id="background_image_preview" class="mt-2">
                                            @php
                                            $mediaId = old('background_image_id', optional($item->backgroundImage)->uuid ?? null);
                                            @endphp

                                            @if($mediaId && $item->backgroundImage)
                                            @php
                                            $ext = strtolower($item->backgroundImage->extension ?? '');
                                            $url = $item->backgroundImage->file_path ? asset('storage/' . $item->backgroundImage->file_path) : ($item->backgroundImage->external_url ?? null);
                                            $isImage = in_array($ext, ['jpg','jpeg','png','gif','webp','svg']);
                                            @endphp
                                            @if($url)
                                            @if($isImage)
                                            <div>
                                                <img src="{{ $url }}"
                                                    alt="{{ $item->backgroundImage->title }}"
                                                    class="img-thumbnail"
                                                    style="max-width: 50px; max-height: 50px; object-fit: cover;">
                                                <!-- <div class="small text-muted mt-1">
                                                    ID: {{ $mediaId }} | {{ $item->backgroundImage->title }}
                                                </div> -->
                                            </div>
                                            @else
                                            <div>
                                                <a href="{{ $url }}" target="_blank" rel="noopener">
                                                    {{ $item->backgroundImage->title ?? 'View file' }} ({{ strtoupper($ext) }})
                                                </a>
                                                <div class="small text-muted mt-1">
                                                    ID: {{ $item->backgroundImage->uuid }}
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
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mt-3">
                                                <label class="form-label">Image External URL</label>
                                                <input type="text" name="background_image_url" class="form-control" placeholder="or external URL" value="{{ old('background_image_url', $item->background_image_url ?? '') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mt-3">
                                                <label class="form-label">Section Background Color</label>
                                                <div class="color-picker-group">
                                                    @php
                                                        $bgColor = strtoupper(old('background_color', $item->background_color ?? ''));
                                                        if ($bgColor === '' || !preg_match('/^#([0-9a-f]{6})$/i', $bgColor)) {
                                                            $bgColor = '#FFFFFF';
                                                        }
                                                    @endphp
                                                    <button type="button"
                                                            class="pickr-trigger"
                                                            data-color-target="color-input-background_color"
                                                            data-default-color="#FFFFFF">
                                                        <span class="d-flex align-items-center gap-2">
                                                            <span class="color-dot" style="background: {{ $bgColor }}; width: 1.75rem; height: 1.75rem; border-radius: 50%; border: 1px solid rgba(0, 0, 0, 0.1);"></span>
                                                            <span class="color-value">{{ $bgColor ?: 'No color' }}</span>
                                                        </span>
                                                        <i class="bx bx-droplet"></i>
                                                    </button>
                                                    <input type="hidden" name="background_color" id="color-input-background_color" value="{{ $bgColor }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group mt-3">
                                                <label class="form-label">Background Gradient Color</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mt-3">
                                                <div class="mb-2">
                                                    <small class="text-muted">Color 1 (Start)</small>
                                                </div>
                                                <div class="color-picker-group">
                                                    @php
                                                        $gradientColors = old('background_gradient_color', $item->background_gradient_color ?? '');
                                                        $colors = explode(',', $gradientColors);
                                                        $color1 = isset($colors[0]) ? strtoupper(trim($colors[0])) : '';
                                                        $color2 = isset($colors[1]) ? strtoupper(trim($colors[1])) : '';
                                                        if ($color1 === '' || !preg_match('/^#([0-9a-f]{6})$/i', $color1)) {
                                                            $color1 = '#000000';
                                                        }
                                                        if ($color2 === '' || !preg_match('/^#([0-9a-f]{6})$/i', $color2)) {
                                                            $color2 = '#000000';
                                                        }
                                                    @endphp
                                                    <button type="button"
                                                            class="pickr-trigger"
                                                            data-color-target="color-input-background_gradient_color_1"
                                                            data-default-color="#000000">
                                                        <span class="d-flex align-items-center gap-2">
                                                            <span class="color-dot" style="background: {{ $color1 }}; width: 1.75rem; height: 1.75rem; border-radius: 50%; border: 1px solid rgba(0, 0, 0, 0.1);"></span>
                                                            <span class="color-value">{{ $color1 ?: 'No color' }}</span>
                                                        </span>
                                                        <i class="bx bx-droplet"></i>
                                                    </button>
                                                    <input type="hidden" id="color-input-background_gradient_color_1" value="{{ $color1 }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mt-3">
                                                <div class="mb-2">
                                                    <small class="text-muted">Color 2 (End)</small>
                                                </div>
                                                <div class="color-picker-group">
                                                    <button type="button"
                                                            class="pickr-trigger"
                                                            data-color-target="color-input-background_gradient_color_2"
                                                            data-default-color="#000000">
                                                        <span class="d-flex align-items-center gap-2">
                                                            <span class="color-dot" style="background: {{ $color2 }}; width: 1.75rem; height: 1.75rem; border-radius: 50%; border: 1px solid rgba(0, 0, 0, 0.1);"></span>
                                                            <span class="color-value">{{ $color2 ?: 'No color' }}</span>
                                                        </span>
                                                        <i class="bx bx-droplet"></i>
                                                    </button>
                                                    <input type="hidden" id="color-input-background_gradient_color_2" value="{{ $color2 }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- Combined hidden field that will be submitted --}}
                                    <input type="hidden" name="background_gradient_color" id="color-input-background_gradient_color" value="{{ $gradientColors }}">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mt-3">
                                                <label class="form-label">Button Text</label>
                                                <div class="input-group">
                                                    <input type="text" name="button_text" class="form-control" value="{{ old('button_text', $item->button_text ?? '') }}">
                                                    @php
                                                        $buttonTextColor = strtoupper(old('button_text_color', $item->button_text_color ?? ''));
                                                        if ($buttonTextColor === '' || !preg_match('/^#([0-9a-f]{6})$/i', $buttonTextColor)) {
                                                            $buttonTextColor = '#000000';
                                                        }
                                                    @endphp
                                                    <button type="button"
                                                            class="btn btn-outline-secondary pickr-trigger"
                                                            data-color-target="color-input-button_text_color"
                                                            data-default-color="#000000"
                                                            style="min-width: 50px; border-left: 0;">
                                                        <span class="color-dot" style="background: {{ $buttonTextColor }}; width: 20px; height: 20px; border-radius: 50%; display: inline-block; border: 1px solid rgba(0,0,0,0.1);"></span>
                                                    </button>
                                                    <input type="hidden" name="button_text_color" id="color-input-button_text_color" value="{{ $buttonTextColor }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mt-3">
                                                <label class="form-label">Button Background Color</label>
                                                <div class="color-picker-group">
                                                    @php
                                                        $btnBgColor = strtoupper(old('button_background_color', $item->button_background_color ?? ''));
                                                        if ($btnBgColor === '' || !preg_match('/^#([0-9a-f]{6})$/i', $btnBgColor)) {
                                                            $btnBgColor = '#FFFFFF'; // Default to white
                                                        }
                                                    @endphp
                                                    <button type="button"
                                                            class="pickr-trigger"
                                                            data-color-target="color-input-button_background_color"
                                                            data-default-color="#FFFFFF">
                                                        <span class="d-flex align-items-center gap-2">
                                                            <span class="color-dot" style="background: {{ $btnBgColor }}; width: 1.75rem; height: 1.75rem; border-radius: 50%; border: 1px solid rgba(0, 0, 0, 0.1);"></span>
                                                            <span class="color-value">{{ $btnBgColor ?: 'No color' }}</span>
                                                        </span>
                                                        <i class="bx bx-droplet"></i>
                                                    </button>
                                                    <input type="hidden" name="button_background_color" id="color-input-button_background_color" value="{{ $btnBgColor }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mt-3">
                                                <label class="form-label">No of Record you want to show in this section</label>
                                                <input type="number" name="limit" class="form-control" value="{{ old('limit', $item->limit ?? '') }}" placeholder="Optional limit" min="1">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group mt-3">
                                                <label class="form-label">Description</label>
                                                <div class="d-flex gap-2">
                                                    <textarea name="description" class="form-control" rows="3" style="flex: 1;">{{ old('description', $item->description ?? '') }}</textarea>
                                                    @php
                                                        $descriptionColor = strtoupper(old('text_color', $item->text_color ?? ''));
                                                        if ($descriptionColor === '' || !preg_match('/^#([0-9a-f]{6})$/i', $descriptionColor)) {
                                                            $descriptionColor = '#000000';
                                                        }
                                                    @endphp
                                                    <button type="button"
                                                            class="btn btn-outline-secondary pickr-trigger"
                                                            data-color-target="color-input-text_color"
                                                            data-default-color="#000000"
                                                            style="min-width: 50px; align-self: flex-start; margin-top: 0;">
                                                        <span class="color-dot" style="background: {{ $descriptionColor }}; width: 20px; height: 20px; border-radius: 50%; display: inline-block; border: 1px solid rgba(0,0,0,0.1);"></span>
                                                    </button>
                                                    <input type="hidden" name="text_color" id="color-input-text_color" value="{{ $descriptionColor }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-success">
                                    <i class="bx bx-save"></i> Save Section
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/vendor/libs/pickr/pickr.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        function slugify(text) {
            return text.toString().toLowerCase().trim()
                .replace(/[\s_]+/g, '-')
                .replace(/[^a-z0-9-]+/g, '')
                .replace(/--+/g, '-');
        }

        let slugChanged = false;
        const slugInput = document.getElementById('slug');

        const headingInput = document.getElementById('heading');
        const titleInput = document.getElementById('title');

        if (headingInput && titleInput) {
            headingInput.addEventListener('input', function() {
                // Auto-populate title from heading
                titleInput.value = this.value;

                // Auto-generate slug from heading
                if (!slugChanged && slugInput) {
                    slugInput.value = slugify(this.value);
                }
            });
        }

        if (slugInput) {
            slugInput.addEventListener('input', function() {
                slugChanged = true;
            });
        }

        const pickrDefaults = [
            '#2563EB', '#0EA5E9', '#14B8A6', '#0F172A',
            '#F97316', '#F43F5E', '#A855F7', '#22C55E',
            '#84CC16', '#EAB308', '#FFFFFF', '#111827'
        ];

        function initColorPickers(retryCount = 0) {
            const PickrLib = window.Pickr || window.pickr;

            if (!PickrLib) {
                if (retryCount < 20) {
                    setTimeout(() => initColorPickers(retryCount + 1), 100);
                }
                return;
            }

            document.querySelectorAll('.pickr-trigger').forEach(function(trigger) {
                if (trigger.dataset.pickrAttached === 'true') {
                    return;
                }

                const targetId = trigger.dataset.colorTarget;
                const defaultColor = trigger.dataset.defaultColor || '#FFFFFF';
                const hiddenInput = document.getElementById(targetId);
                const dot = trigger.querySelector('.color-dot');
                const valueLabel = trigger.querySelector('.color-value');

                if (!hiddenInput || !dot) {
                    return;
                }

                const setEmptyState = () => {
                    hiddenInput.value = '';
                    dot.style.backgroundColor = defaultColor;
                    if (valueLabel) {
                        valueLabel.textContent = 'No color';
                    }
                };

                const applyColor = (hex) => {
                    if (!hex || hex === '') {
                        setEmptyState();
                        return;
                    }

                    const normalized = hex.toUpperCase();
                    hiddenInput.value = normalized;
                    dot.style.backgroundColor = normalized;
                    if (valueLabel) {
                        valueLabel.textContent = normalized;
                    }
                };

                // Set initial color - use existing value or default
                const initialColor = hiddenInput.value || defaultColor;
                if (hiddenInput.value && hiddenInput.value !== '') {
                    applyColor(hiddenInput.value);
                } else {
                    // Show default color in dot but don't save to input yet
                    dot.style.backgroundColor = defaultColor;
                }

                let pickrInstance;

                try {
                    pickrInstance = PickrLib.create({
                        el: trigger,
                        useAsButton: true,
                        theme: 'nano',
                        default: initialColor || defaultColor,
                        swatches: pickrDefaults,
                        components: {
                            preview: true,
                            opacity: true,
                            hue: true,
                            interaction: {
                                hex: true,
                                rgba: true,
                                input: true,
                                clear: true,
                                save: true
                            }
                        }
                    });
                } catch (error) {
                    console.error('Unable to initialize color picker', error);
                    return;
                }

                trigger.dataset.pickrAttached = 'true';

                const toHex = (color) => {
                    if (!color) {
                        return '';
                    }

                    const hexa = color.toHEXA().slice(0, 3);
                    const sixDigit = hexa
                        .map((component) => component.padStart(2, '0'))
                        .join('');

                    return '#' + sixDigit.toUpperCase();
                };

                const syncColor = (color) => {
                    applyColor(toHex(color));

                    // If this is a gradient color picker, combine both colors
                    if (targetId.includes('background_gradient_color_1') || targetId.includes('background_gradient_color_2')) {
                        updateGradientColor();
                    }
                };

                pickrInstance.on('change', syncColor);
                pickrInstance.on('save', (color) => {
                    syncColor(color);
                    pickrInstance.hide();
                });
                pickrInstance.on('clear', () => {
                    hiddenInput.value = '';
                    dot.style.backgroundColor = defaultColor;
                    if (valueLabel) {
                        valueLabel.textContent = 'No color';
                    }
                    pickrInstance.setColor(defaultColor);
                    pickrInstance.hide();

                    // Update combined gradient color if needed
                    if (targetId.includes('background_gradient_color_1') || targetId.includes('background_gradient_color_2')) {
                        updateGradientColor();
                    }
                });
                pickrInstance.on('swatchselect', (color) => syncColor(color));
            });

            // Reset removal flags when new media is selected
            $('#background_image_id').on('change', function() {
                if ($(this).val()) {
                    $('#remove_background_image').val('0');
                }
            });
        }

        // Function to combine gradient colors into single field
        function updateGradientColor() {
            const color1Input = document.getElementById('color-input-background_gradient_color_1');
            const color2Input = document.getElementById('color-input-background_gradient_color_2');
            const combinedInput = document.getElementById('color-input-background_gradient_color');

            if (color1Input && color2Input && combinedInput) {
                const color1 = color1Input.value || '#000000';
                const color2 = color2Input.value || '#000000';
                combinedInput.value = color1 + ',' + color2;
            }
        }

        initColorPickers();

        // Initialize gradient color on page load
        updateGradientColor();

        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('invalid', function (event) {
                const tabPane = event.target.closest('.tab-pane');
                if (tabPane && !tabPane.classList.contains('show')) {
                    const trigger = document.querySelector(`[data-bs-toggle="tab"][href="#${tabPane.id}"]`);
                    if (trigger && typeof bootstrap !== 'undefined' && bootstrap.Tab) {
                        const tab = new bootstrap.Tab(trigger);
                        tab.show();
                    }
                }
            }, true);
        }

        // Remove background image handler (edit case)
        const removeBgBtn = document.getElementById('remove_background_image_btn');
        if (removeBgBtn) {
            removeBgBtn.addEventListener('click', function () {
                const bgInput = document.getElementById('background_image_id');
                const removeInput = document.getElementById('remove_background_image');
                const preview = document.getElementById('background_image_preview');

                if (bgInput) bgInput.value = '';
                if (removeInput) removeInput.value = '1';
                if (preview) preview.innerHTML = '<span class="text-muted">No media selected</span>';
            });
        }
    });
</script>
@endpush
