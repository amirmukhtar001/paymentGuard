@extends('layouts.' . config('settings.active_layout'))

@push('stylesheets')
{{-- CKEditor height + init --}}
<style>
    /* Give CKEditor a comfortable height */
    .ck-editor__editable_inline {
        min-height: 250px;
    }
</style>
@endpush

@section('content')

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
                                    ? route('settings.cabinetmembers.update', $item->uuid)
                                    : route('settings.cabinetmembers.store') }}">
                            @csrf
                            @if($item->exists)
                            @method('PUT')
                            @endif
                            <div class="row">
                                <div class="col-md-6">
                                </div>
                                <div class="col-md-4">
                                </div>

                                <div class="col-md-2">
                                    {{-- Preview container --}}
                                    <div id="member_media_preview" class="mt-2">
                                        @php
                                        $mediaId = old('member_media_id', $item->media->uuid ?? null);
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
                                                style="max-width: 50px; max-height: 50px; object-fit: cover;">

                                        </div>
                                        @else
                                        <div>
                                            <a href="{{ $url }}" target="_blank" rel="noopener">
                                                {{ $item->media->title ?? 'View file' }} ({{ strtoupper($ext) }})
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

                            {{-- BASIC INFO --}}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label req">Name</label>
                                        @error('name')
                                        <span class="text-danger d-block">{{ $message }}</span>
                                        @enderror
                                        <input type="text"
                                            name="name"
                                            class="form-control"
                                            value="{{ old('name', $item->name ?? '') }}"
                                            required>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Date of Birth</label>
                                        @error('dob')
                                        <span class="text-danger d-block">{{ $message }}</span>
                                        @enderror
                                        <input type="date"
                                            name="dob"
                                            class="form-control"
                                            value="{{ old('dob', optional($item->dob)->format('Y-m-d')) }}">
                                    </div>
                                </div>

                                {{-- Profile Media --}}
                                <div class="col-md-3">

                                    <div class="form-group">
                                        <label class="form-label">Profile Image</label>

                                        {{-- hidden input to store selected media id --}}
                                        <input type="hidden"
                                            name="member_media_id"
                                            id="member_media_id"
                                            value="{{ old('member_media_id', $item->media->uuid ?? '') }}">

                                        <button type="button"
                                            class="btn btn-outline-primary open-media-manager"
                                            data-mode="single"
                                            data-target-input="#member_media_id"
                                            data-preview-target="#member_media_preview">
                                            Choose Profile Image
                                        </button>


                                    </div>
                                </div>

                            </div>

                            {{-- CONTACT INFO --}}
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Contact No</label>
                                        @error('contact_no')
                                        <span class="text-danger d-block">{{ $message }}</span>
                                        @enderror
                                        <input type="text"
                                            name="contact_no"
                                            class="form-control"
                                            value="{{ old('contact_no', $item->contact_no ?? '') }}">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Office No</label>
                                        <input type="text"
                                            name="office_no"
                                            class="form-control"
                                            value="{{ old('office_no', $item->office_no ?? '') }}">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Email</label>
                                        <input type="email"
                                            name="email"
                                            class="form-control"
                                            value="{{ old('email', $item->email ?? '') }}">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Facebook Page</label>
                                        <input type="url"
                                            name="facebook_page"
                                            class="form-control"
                                            value="{{ old('facebook_page', $item->facebook_page ?? '') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Twitter / X Page</label>
                                        <input type="url"
                                            name="twitter_page"
                                            class="form-control"
                                            value="{{ old('twitter_page', $item->twitter_page ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="sort_order" class="form-label">
                                            Sort Order
                                        </label>
                                        <span class="help">
                                            @if(Session::has('errors'))
                                            {!! Session::get('errors')->first('sort_order') !!}
                                            @endif
                                        </span>
                                        <input type="number"
                                            name="sort_order"
                                            id="sort_order"
                                            class="form-control"
                                            value="{{ old('sort_order', $item->sort_order ?? 0) }}">
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label req">Web Sites</label>
                                        <span class="help">
                                            @if($errors->has('company_id')) {!! $errors->first('company_id') !!} @endif
                                        </span>
                                        @include('components.company_field', [
                                        'companies' => $companies,
                                        'select_id' => 'company_id',
                                        'label' => 'Web Site',
                                        'selected' => $item->company_id ?? null
                                        ])

                                        <!-- @error('company_ids')
                                        <span class="text-danger d-block">{{ $message }}</span>
                                        @enderror -->

                                        <!-- <div class="d-flex gap-2 mb-2">
                                            <button type="button" class="btn btn-sm btn-outline-primary" id="companies_select_all">
                                                Select All
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary" id="companies_clear_all">
                                                Clear All
                                            </button>
                                        </div> -->

                                        <!-- @php
                                        $selectedCompanies = old(
                                        'company_ids',
                                        $item->exists ? $item->companies->pluck('id')->toArray() : []
                                        );
                                        @endphp

                                        <select name="company_ids[]" id="company_ids" class="form-control select2" multiple>
                                            @foreach($companies as $id => $name)
                                            <option value="{{ $id }}" {{ in_array($id, $selectedCompanies) ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                            @endforeach
                                        </select> -->
                                    </div>
                                </div>
                            </div>


                            <hr>

                            {{-- CURRENT POSITION INFO --}}
                            @php
                            $current = $item->currentPosition ?? null;
                            @endphp

                            <h5>Current Position</h5>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label req">Position Type</label>
                                        <select name="position_type_id"
                                            class="form-control select2"
                                            required>
                                            <option value="">Select Position Type</option>
                                            @foreach($positionTypes as $id => $name)
                                            <option value="{{ $id }}"
                                                {{ old('position_type_id', $current->position_type_id ?? '') == $id ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label req">Department</label>
                                        <select name="department_id" class="form-control select2">
                                            <option value="">Select Department</option>
                                            @foreach($departments as $id => $name)
                                            <option value="{{ $id }}"
                                                {{ old('department_id', $current->department_id ?? '') == $id ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Party</label>
                                        <select name="party_id"
                                            class="form-control select2">
                                            <option value="">Select Party</option>
                                            @foreach($parties as $id => $name)
                                            <option value="{{ $id }}"
                                                {{ old('party_id', $current->party_id ?? '') == $id ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div> 
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Halqa</label>
                                        <select name="halqa_id"
                                            class="form-control select2">
                                            <option value="">Select Halqa</option>
                                            @foreach($halqas as $id => $code)
                                            <option value="{{ $id }}"
                                                {{ old('halqa_id', $current->halqa_id ?? '') == $id ? 'selected' : '' }}>
                                                {{ $code }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            {{-- DATES + POSITION MEDIA --}}
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label req">Working From</label>
                                        <input type="date"
                                            name="working_from_date"
                                            class="form-control"
                                            value="{{ old('working_from_date', optional($current->working_from_date ?? null)->format('Y-m-d')) }}"
                                            required>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Working Till</label>
                                        <input type="date"
                                            name="working_till_date"
                                            class="form-control"
                                            value="{{ old('working_till_date', optional($current->working_till_date ?? null)->format('Y-m-d')) }}">
                                        <small class="text-muted">Leave empty if still in office</small>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Position Media (Notification / Order)</label>

                                        <input type="hidden"
                                            name="position_media_id"
                                            id="position_media_id"
                                            value="{{ old('position_media_id', $current->media->uuid ?? '') }}">

                                        <button type="button"
                                            class="btn btn-outline-primary open-media-manager"
                                            data-mode="single"
                                            data-target-input="#position_media_id"
                                            data-preview-target="#position_media_preview">
                                            Choose Position Media
                                        </button>

                                        <div id="position_media_preview" class="mt-2">
                                            @php
                                            $posMediaId = old('position_media_id', $current->media->uuid ?? null);
                                            @endphp

                                            @if($posMediaId && isset($current) && $current->media)
                                            @php
                                            $ext = strtolower($current->media->extension ?? '');
                                            $url = $current->media->file_path ? asset('storage/' . $current->media->file_path) : null;
                                            $isImage = in_array($ext, ['jpg','jpeg','png','gif','webp','svg']);
                                            @endphp

                                            @if($url)
                                            @if($isImage)
                                            <div>
                                                <img src="{{ $url }}"
                                                    alt="{{ $current->media->title }}"
                                                    class="img-thumbnail"
                                                    style="max-width: 150px; max-height: 150px; object-fit: cover;">
                                                <div class="small text-muted mt-1">
                                                    ID: {{ $posMediaId }} | {{ $current->media->title }}
                                                </div>
                                            </div>
                                            @else
                                            <div>
                                                <a href="{{ $url }}" target="_blank" rel="noopener">
                                                    {{ $current->media->title ?? 'View file' }} ({{ strtoupper($ext) }})
                                                </a>
                                                <div class="small text-muted mt-1">
                                                    ID: {{ $posMediaId }}
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

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label req">Status</label>
                                        @error('status')
                                        <span class="text-danger d-block">{{ $message }}</span>
                                        @enderror

                                        @php
                                        $selectedStatus = old('status', $item->status ?? \App\Enums\StatusEnum::ACTIVE->value);
                                        @endphp

                                        <select name="status" class="form-control select2" required>
                                            @foreach(\App\Enums\StatusEnum::cases() as $case)
                                            <option value="{{ $case->value }}" {{ $selectedStatus === $case->value ? 'selected' : '' }}>
                                                {{ $case->value }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Member Type</label>
                                        @error('member_type')
                                        <span class="text-danger d-block">{{ $message }}</span>
                                        @enderror

                                        @php
                                        $selectedMember = old('member_type', $item->member_type ?? \App\Enums\MemberTypesEnum::CABINETMEMBER->value);
                                        @endphp

                                        <select name="member_type" class="form-control select2" required>
                                            @foreach(\App\Enums\MemberTypesEnum::cases() as $case)
                                            <option value="{{ $case->value }}" {{ $selectedMember === $case->value ? 'selected' : '' }}>
                                                {{ $case->value }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>



                                <div class="col-md-6">
                                    <!-- Checkbox to make this employee a leader -->
                                    <div class="form-group mt-4">
                                        <div class="form-check">
                                            <input type="hidden" name="make_leader" value="no">
                                            <input type="checkbox"
                                                name="make_leader"
                                                id="make_leader"
                                                value="yes"
                                                {{ old('make_leader', $item->make_leader ?? 'no') === 'yes' ? 'checked' : '' }}
                                                class="form-check-input">

                                            <label class="form-check-label" for="make_leader">Show this leader on home page.</label>
                                        </div>
                                    </div>

                                    <!-- Leader-specific fields (shown when checkbox is checked) -->
                                    <div id="leader_fields" style="display: none;">
                                        <div class="form-group">
                                            <label>Leadership Position</label>
                                            <input type="text" name="leader_position" class="form-control" placeholder="e.g., CEO, Manager">
                                        </div>
                                    </div>
                                </div>
                            </div>





                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Message / Quote (Optional)</label>
                                        <textarea name="message" id="body-editor" class="form-control" rows="30" placeholder="Message">{{ old('message', $item->message ?? '') }}</textarea>
                                    </div>
                                </div>
                            </div>


                            {{-- BUTTONS --}}
                            <div class="row mt-3">
                                <div class="col-12">
                                    <a href="{{ route('settings.cabinetmembers.list') }}" class="btn btn-warning">
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


        $('#member_media_id').on('change', function() {
            $('#member_media_id_label').text($(this).val() || '-');
        });

        $('#position_media_id').on('change', function() {
            $('#position_media_id_label').text($(this).val() || '-');
        });

        // Show/hide leader fields based on checkbox
        document.getElementById('make_leader').addEventListener('change', function() {
            //document.getElementById('leader_fields').style.display = this.checked ? 'block' : 'none';
        });

    });

    $(function() {
        const $companies = $('#company_ids').select2();

        $('#companies_select_all').on('click', function() {
            const allVals = $('#company_ids option').map(function() {
                return $(this).val();
            }).get();

            $companies.val(allVals).trigger('change'); // ✅ select all
        });

        $('#companies_clear_all').on('click', function() {
            $companies.val([]).trigger('change'); // ✅ clear all
        });
    });
</script>
@endpush