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

@php
use App\Enums\CaderEnum;
use App\Enums\BPSEnum;
use App\Enums\DesignationEnum;
use App\Enums\DisplayOnHomeEnum;

$caderOptions = CaderEnum::cases();
$bpsOptions = BPSEnum::cases();
$designationOptions = DesignationEnum::cases();
$displayOnHomeOptions = DisplayOnHomeEnum::cases();
@endphp

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
                                            ? route('settings.employees.update', $item->uuid)
                                            : route('settings.employees.store') }}">
                            @csrf
                            @if ($item->exists)
                            @method('PUT')
                            @endif

                            <div class="row">
                                <div class="col-md-6"></div>
                                <div class="col-md-4"></div>

                                <div class="col-md-2">
                                    {{-- Picture preview --}}
                                    <div id="picture_media_preview" class="mt-2">
                                        @php
                                        $pictureMediaUuid = old('picture_media_id', $item->picture->uuid ?? null);
                                        @endphp

                                        @if($pictureMediaUuid && $item->picture)
                                        @php
                                        $ext = strtolower($item->picture->extension ?? '');
                                        $url = $item->picture->file_path
                                        ? asset('storage/' . $item->picture->file_path)
                                        : null;
                                        $isImage = in_array($ext, ['jpg','jpeg','png','gif','webp','svg']);
                                        @endphp

                                        @if($url)
                                        @if($isImage)
                                        <div>
                                            <img src="{{ $url }}"
                                                alt="{{ $item->picture->title }}"
                                                class="img-thumbnail"
                                                style="max-width: 50px; max-height: 50px; object-fit: cover;">
                                        </div>
                                        @else
                                        <div>
                                            <a href="{{ $url }}" target="_blank" rel="noopener">
                                                {{ $item->picture->title ?? 'View file' }} ({{ strtoupper($ext) }})
                                            </a>
                                        </div>
                                        @endif
                                        @else
                                        <span class="text-muted">No media file path</span>
                                        @endif
                                        @else
                                        <span class="text-muted">No picture selected</span>
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
                                        <label class="form-label">Working Since</label>
                                        @error('working_since')
                                        <span class="text-danger d-block">{{ $message }}</span>
                                        @enderror
                                        <input type="date"
                                            name="working_since"
                                            class="form-control"
                                            value="{{ old('working_since', optional($item->working_since)->format('Y-m-d')) }}">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Worked Till</label>
                                        @error('worked_till')
                                        <span class="text-danger d-block">{{ $message }}</span>
                                        @enderror
                                        <input type="date"
                                            name="worked_till"
                                            class="form-control"
                                            value="{{ old('worked_till', optional($item->worked_till)->format('Y-m-d')) }}">
                                        <small class="text-muted">Leave empty if currently serving</small>
                                    </div>
                                </div>
                            </div>

                            {{-- ENUM + COMPANY / DEPARTMENT / POSITION --}}
                            <div class="row">

                                {{-- Cader --}}
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Cader</label>
                                        @error('cader')
                                        <span class="text-danger d-block">{{ $message }}</span>
                                        @enderror
                                        <select name="cader" class="form-control select2">
                                            <option value="">Select Cader</option>
                                            @foreach($caderOptions as $opt)
                                            <option value="{{ $opt->value }}"
                                                {{ old('cader', $item->cader?->value ?? '') === $opt->value ? 'selected' : '' }}>
                                                {{ $opt->value }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- BPS --}}
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">BPS</label>
                                        @error('bps')
                                        <span class="text-danger d-block">{{ $message }}</span>
                                        @enderror
                                        <select name="bps" class="form-control select2">
                                            <option value="">Select BPS</option>
                                            @foreach($bpsOptions as $opt)
                                            <option value="{{ $opt->value }}"
                                                {{ old('bps', $item->bps?->value ?? '') === $opt->value ? 'selected' : '' }}>
                                                {{ $opt->value }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- Designation --}}
                                <div class="col-md-3">
                                    <div class="form-group">
                                         <label class="form-label">Designation</label>
                                         <span class="help">
                                            @if($errors->has('designation_id')) {!! $errors->first('designation_id') !!} @endif
                                        </span>
                                           <select name="designation" id="designation_id" class="form-control select2">
                                            <option value="">-- Select Designation --</option>

                                            @foreach ($designations as $designation)
                                            <option value="{{ $designation->id }}"
                                                {{ old('designation_id', $item->designation_id ?? '') == $designation->id ? 'selected' : '' }}>
                                                {{ $designation->title }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- Sort Order --}}
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="sort_order" class="form-label">Sort Order</label>
                                        @error('sort_order')
                                        <span class="text-danger d-block">{{ $message }}</span>
                                        @enderror
                                        <input type="number"
                                            name="sort_order"
                                            id="sort_order"
                                            class="form-control"
                                            value="{{ old('sort_order', $item->sort_order ?? 0) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                {{-- Company (Web Site) --}}
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label req">Web Site</label>
                                        @if($errors->has('company_id'))
                                        <span class="text-danger d-block">{{ $errors->first('company_id') }}</span>
                                        @endif

                                        @include('components.company_field', [
                                        'companies' => $companies,
                                        'select_id' => 'company_id',
                                        'label' => 'Web Site',
                                        'selected' => old('company_id', $item->company_id ?? null)
                                        ])
                                    </div>
                                </div>

                                {{-- Department --}}
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Department</label>
                                        @error('department_id')
                                        <span class="text-danger d-block">{{ $message }}</span>
                                        @enderror
                                        <select name="department_id"
                                            class="form-control select2">
                                            <option value="">Select Department</option>
                                            @foreach($departments as $id => $name)
                                            <option value="{{ $id }}"
                                                {{ old('department_id', $item->department_id ?? '') == $id ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
<input type="hidden" name="position_type_id" value="1">
                                {{-- Position Type --}}
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label req">Position Type</label>
                                        @error('position_type_id')
                                        <span class="text-danger d-block">{{ $message }}</span>
                                        @enderror
                                        <select name="position_type_id"
                                            class="form-control select2"
                                            required>
                                            <option value="">Select Position Type</option>
                                            @foreach($positionTypes as $id => $name)
                                            <option value="{{ $id }}"
                                                {{ old('position_type_id', $item->position_type_id ?? '') == $id ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                
                                {{-- Display on Home --}}
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label req">Display on Home In Message Section</label>
                                        @error('display_on_home')
                                        <span class="text-danger d-block">{{ $message }}</span>
                                        @enderror
                                        <select name="display_on_home" class="form-control" required>
                                            @foreach($displayOnHomeOptions as $opt)
                                            <option value="{{ $opt->value }}"
                                                {{ old('display_on_home', $item->display_on_home?->value ?? 'no') === $opt->value ? 'selected' : '' }}>
                                                {{ ucfirst($opt->value) }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                            </div>

                            {{-- DISPLAY + MEDIA FIELDS --}}
                            <div class="row">

                                {{-- Picture Media --}}
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Picture (Profile)</label>

                                        <input type="hidden"
                                            name="picture_media_id"
                                            id="picture_media_id"
                                            value="{{ old('picture_media_id', $item->picture->uuid ?? '') }}">

                                        <button type="button"
                                            class="btn btn-outline-primary open-media-manager"
                                            data-mode="single"
                                            data-target-input="#picture_media_id"
                                            data-preview-target="#picture_media_preview">
                                            Choose Picture
                                        </button>
                                    </div>
                                </div>

                                {{-- Notification Media --}}
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="form-label">Notification (Order / File)</label>

                                        <input type="hidden"
                                            name="notification_media_id"
                                            id="notification_media_id"
                                            value="{{ old('notification_media_id', $item->notificationMedia->uuid ?? '') }}">

                                        <button type="button"
                                            class="btn btn-outline-primary open-media-manager"
                                            data-mode="single"
                                            data-target-input="#notification_media_id"
                                            data-preview-target="#notification_media_preview">
                                            Choose Notification Media
                                        </button>

                                        <div id="notification_media_preview" class="mt-2">
                                            @php
                                            $notifMediaUuid = old('notification_media_id', $item->notificationMedia->uuid ?? null);
                                            @endphp

                                            @if($notifMediaUuid && $item->notificationMedia)
                                            @php
                                            $extN = strtolower($item->notificationMedia->extension ?? '');
                                            $urlN = $item->notificationMedia->file_path
                                            ? asset('storage/' . $item->notificationMedia->file_path)
                                            : null;
                                            $isImageN = in_array($extN, ['jpg','jpeg','png','gif','webp','svg']);
                                            @endphp

                                            @if($urlN)
                                            @if($isImageN)
                                            <div>
                                                <img src="{{ $urlN }}"
                                                    alt="{{ $item->notificationMedia->title }}"
                                                    class="img-thumbnail"
                                                    style="max-width: 150px; max-height: 150px; object-fit: cover;">
                                            </div>
                                            @else
                                            <div>
                                                <a href="{{ $urlN }}" target="_blank" rel="noopener">
                                                    {{ $item->notificationMedia->title ?? 'View file' }} ({{ strtoupper($extN) }})
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


                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Message / Quote (Optional)</label>
                                        <textarea class="form-control editor-ckeditor" rows="4" name="message" cols="50" id="content">{{ old('message', $item->message ?? '') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            {{-- BUTTONS --}}
                            <div class="row mt-3">
                                <div class="col-12">
                                    <a href="{{ route('settings.employees.list') }}" class="btn btn-warning">
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
<!-- <script src="https://cdn.ckeditor.com/4.22.1/full-all/ckeditor.js"></script> --> 
<script> 
   var RV_MEDIA_URL = {
            'media_upload_from_editor': "{{ route('settings.media.images.upload.fromeditor') }}",
        }; 
</script>

 <script src="{{ asset('assets/vendor/js/editor.js?v=5.22') }}"></script>
    <script src="{{ asset('assets/vendor/js/ckeditor.js?v=5.22') }}"></script>
<script>
    $(document).ready(function() { 
        // function initCKEditor() {
        //     if (typeof CKEDITOR !== 'undefined' && $('#body-editor').length) {
        //         CKEDITOR.replace('body-editor', {
        //                 height: 400,
        //                 extraAllowedContent: 'iframe[*]',
        //                 extraPlugins: 'image2',
        //                 // removePlugins: 'exportpdf',
        //                 removePlugins: 'image',
        //                 on: {
        //                     instanceReady: function() {
        //                         setTimeout(function() {
        //                             $('.cke_warning, .cke_notification_warning').remove();
        //                         }, 500);
        //                     }
        //                 }
        //             });
        //         return true;
        //     }
        //     return false;
        // }

        // if (!initCKEditor()) {
        //     let attempts = 0;
        //     const interval = setInterval(function() {
        //         if (initCKEditor() || ++attempts >= 10) {
        //             clearInterval(interval);
        //         }
        //     }, 200);
        // }


     
    });
</script>
@endpush