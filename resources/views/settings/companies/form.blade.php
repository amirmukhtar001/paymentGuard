@extends('layouts.' . config('settings.active_layout'))

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

        .input-group textarea+.pickr-trigger {
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

       .card-body .row {
    margin-bottom: 10px; /* adjust as needed */
}


    </style>
@endpush

@push('scripts')
    <script src="{{ asset('assets/vendor/libs/pickr/pickr.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function () {

            $("#check-domain-prefix").on('click', function () {
                var prefixInput = $("#domain_prefix_input")
                var prefix = prefixInput.val().trim()
                var feedback = $("#domain-prefix-feedback")
                var button = $(this)

                feedback.removeClass('text-success text-danger').text('')

                if (!prefix) {
                    feedback.addClass('text-danger').text('Enter a domain prefix first.')
                    return
                }

                button.prop('disabled', true).text('Checking...')

                $.ajax({
                    type: 'post',
                    url: '{{ route("settings.companies.check-domain-prefix") }}',
                    data: {
                        domain_prefix: prefix,
                        company_id: button.data('company-id'),
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (res) {
                        var message = res.message || 'Request completed.'
                        if (res.exists) {
                            feedback.addClass('text-danger').text(message)
                        } else {
                            feedback.addClass('text-success').text(message)
                        }
                    },
                    error: function (xhr) {
                        var message = 'Unable to validate domain prefix.'
                        if (xhr.responseJSON && xhr.responseJSON.errors && xhr.responseJSON.errors.domain_prefix) {
                            message = xhr.responseJSON.errors.domain_prefix[0]
                        }
                        feedback.addClass('text-danger').text(message)
                    },
                    complete: function () {
                        button.prop('disabled', false).text('Check')
                    }
                })
            })

            // ===== Color picker logic (same pattern as website sections) =====

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

                document.querySelectorAll('.pickr-trigger').forEach(function (trigger) {
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

                    const initialColor = hiddenInput.value || defaultColor;
                    if (hiddenInput.value && hiddenInput.value !== '') {
                        applyColor(hiddenInput.value);
                    } else {
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
                        if (targetId.includes('theme_gradient_color_1') || targetId.includes('theme_gradient_color_2')) {
                            updateThemeGradientColor();
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
                        if (targetId.includes('theme_gradient_color_1') || targetId.includes('theme_gradient_color_2')) {
                            updateThemeGradientColor();
                        }
                    });
                    pickrInstance.on('swatchselect', (color) => syncColor(color));
                });
            }

            // Function to combine gradient colors into single field
            function updateThemeGradientColor() {
                const color1Input = document.getElementById('color-input-theme_gradient_color_1');
                const color2Input = document.getElementById('color-input-theme_gradient_color_2');
                const combinedInput = document.getElementById('color-input-theme_gradient_color');

                if (color1Input && color2Input && combinedInput) {
                    const color1 = color1Input.value || '';
                    const color2 = color2Input.value || '';
                    // Only combine if at least one color is set
                    if (color1 || color2) {
                        combinedInput.value = (color1 || '') + ',' + (color2 || '');
                    } else {
                        combinedInput.value = '';
                    }
                }
            }

            initColorPickers();

            // Initialize gradient color on page load
            updateThemeGradientColor();

            // When an invalid field is inside a hidden tab, switch to that tab
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('invalid', function (event) {
                    const tabPane = event.target.closest('.tab-pane');
                    if (tabPane && !tabPane.classList.contains('show')) {
                        const trigger = document.querySelector(
                            `[data-bs-toggle="tab"][href="#${tabPane.id}"]`
                        );
                        if (trigger && typeof bootstrap !== 'undefined' && bootstrap.Tab) {
                            const tab = new bootstrap.Tab(trigger);
                            tab.show();
                        }
                    }
                }, true);
            }

        })
    </script>
@endpush

@section('content')

    <div class="row">
        <div class="col-12">

            <div class="card">
                <div class="card-header header-elements-inline">
                    <h5 class="card-title">{{ $title }}</h5>
                    <div class="header-elements">
                        <div class="form-check form-check-right form-check-switchery form-check-switchery-sm">
                        </div>
                    </div>
                </div>

                <div class="card-body">

                    <div class="row">
                        <div class="col-12">

                            <form method="POST"
                                action="{{ $item->exists ? route('settings.companies.update', \Illuminate\Support\Facades\Crypt::encrypt($item->id)) : route('settings.companies.store') }}"
                                enctype="multipart/form-data">
                                @csrf
                                @if($item->exists)
                                    @method('PUT')
                                @endif

                                {{-- Tabs --}}
                                <ul class="nav nav-tabs mb-3" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-bs-toggle="tab" href="#tab-basic" role="tab">
                                            Basic Info
                                        </a>
                                    </li>
                                    </ul>

                                <div class="tab-content">

                                    {{-- ================= TAB 1: BASIC INFO (ALL YOUR OLD FIELDS) ================= --}}
                                    <div class="tab-pane fade show active" id="tab-basic" role="tabpanel">

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="title"
                                                        class="form-label req">{{ config('settings.company_title') }}
                                                        Web Site Name/ Title</label>
                                                    <span class="help">@if(session()->has('errors'))
                                                    {!! session()->get('errors')->first('title') !!}@endif</span>
                                                    <textarea name="title" id="title" class="form-control"
                                                        required>{{ old('title', $item->title ?? '') }}</textarea>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="parent_id" class="form-label">Select Parent Web Site
                                                        (Optional)
                                                        {{ config('settings.company_title') }}</label>
                                                    <span class="help">@if(Session::has('errors'))
                                                    {!! Session::get('errors')->first('parent_id') !!} @endif</span>
                                                    <select name="parent_id" id="parent_id" class="form-control select2">
                                                        <option value="">Select Parent
                                                            {{ config('settings.company_title') }}
                                                        </option>
                                                        @foreach($companies_dd as $key => $company)
                                                            <option value="{{ $key }}" {{ old('parent_id', $item->parent_id ?? '') == $key ? 'selected' : '' }}>{{ $company }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- (Your old commented-out blocks can stay commented out as they were) --}}

                                        <div class="row mt-4">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="domain" class="form-label">Domain</label>
                                                    <input type="text" name="domain" id="domain" class="form-control"
                                                        value="{{ old('domain', $item->domain ?? 'kp.gov.pk') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="short_code" class="form-label">Short Code</label>
                                                    <input type="text" name="short_code" id="short_code" class="form-control"
                                                        value="{{ old('short_code', $item->short_code ?? '') }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-2">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="company_type_id" class="control-label req">Select Web Site
                                                        Type</label>
                                                    <span class="help">@if(Session::has('errors'))
                                                        {!! Session::get('errors')->first('company_type_id') !!}
                                                    @endif</span>
                                                    <select name="company_type_id" id="company_type_id"
                                                        class="form-control select2" required>
                                                        <option value="">Select a Type</option>
                                                        @foreach($types as $key => $type)
                                                            <option value="{{ $key }}" {{ old('company_type_id', $item->company_type_id ?? '') == $key ? 'selected' : '' }}>
                                                                {{ $type }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Domain Prefix</label>
                                                    <div class="input-group">
                                                        <input type="text" name="domain_prefix" id="domain_prefix_input"
                                                            class="form-control"
                                                            value="{{ old('domain_prefix', $item->domain_prefix ?? '') }}">
                                                        <button type="button" class="btn btn-outline-secondary btn-sm"
                                                            id="check-domain-prefix"
                                                            data-company-id="{{ $item->id ?? '' }}">
                                                            Check
                                                        </button>
                                                    </div>
                                                    <small class="form-text" id="domain-prefix-feedback"></small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Contact Phone</label>
                                                    <input type="text" name="contact_phone" class="form-control"
                                                        value="{{ old('contact_phone', $item->contact_phone ?? '') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Contact Email</label>
                                                    <input type="email" name="contact_email" class="form-control"
                                                        value="{{ old('contact_email', $item->contact_email ?? '') }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Address Line 1</label>
                                                    <input type="text" name="address_line1" class="form-control"
                                                        value="{{ old('address_line1', $item->address_line1 ?? '') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Address Line 2</label>
                                                    <input type="text" name="address_line2" class="form-control"
                                                        value="{{ old('address_line2', $item->address_line2 ?? '') }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Postal Code</label>
                                                    <input type="text" name="postal_code" class="form-control"
                                                        value="{{ old('postal_code', $item->postal_code ?? '') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label req">Status</label>
                                                    @php
                                                        $statusValue = old('status', $item->status ?? 'draft');
                                                    @endphp
                                                    <select name="status" class="form-control" required>
                                                        <option value="draft" {{ $statusValue === 'draft' ? 'selected' : '' }}>Draft</option>
                                                        <option value="active" {{ $statusValue === 'active' ? 'selected' : '' }}>Active</option>
                                                        <option value="inactive" {{ $statusValue === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                                        <option value="archived" {{ $statusValue === 'archived' ? 'selected' : '' }}>Archived</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Launched At</label>
                                                    @php
                                                        $launchedAt = old('launched_at');
                                                        if ($launchedAt === null && isset($item)) {
                                                            $launchedAt = optional($item->launched_at)->format('Y-m-d');
                                                        }
                                                    @endphp
                                                    <input type="date" name="launched_at" class="form-control"
                                                        value="{{ $launchedAt }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Deactivated At</label>
                                                    @php
                                                        $deactivatedAt = old('deactivated_at');
                                                        if ($deactivatedAt === null && isset($item)) {
                                                            $deactivatedAt = optional($item->deactivated_at)->format('Y-m-d');
                                                        }
                                                    @endphp
                                                    <input type="date" name="deactivated_at" class="form-control"
                                                        value="{{ $deactivatedAt }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="description" class="form-label req">Details</label>
                                                    <span class="help">@if(session()->has('errors'))
                                                    {!! session()->get('errors')->first('description') !!}@endif</span>
                                                    <textarea name="description" id="description"
                                                        class="form-control">{{ old('description', $item->description ?? '') }}</textarea>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div> {{-- /.tab-content --}}

                                <div class="row mt-4">
                                    <div class="col-12">
                                        <a href="{{ route('settings.companies.list') }}" class="btn btn-warning">
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
