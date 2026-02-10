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
                                    ? route('settings.people.update', $item->uuid)
                                    : route('settings.people.store') }}">
                            @csrf
                            @if($item->exists)
                            @method('PUT')
                            @endif

                            {{-- TOP ROW (preview like your sample) --}}
                            <div class="row">
                                <div class="col-md-10"></div>

                                <div class="col-md-2">
                                    <div id="profile_media_preview" class="mt-2">
                                        @php
                                        $mediaUuid = old('profile_media_id', $item->media->uuid ?? null);
                                        @endphp

                                        @if($mediaUuid && $item->media)
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
                                {{-- Name --}}
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label req">Name</label>
                                        @error('name') <span class="text-danger d-block">{{ $message }}</span> @enderror
                                        <input type="text"
                                            name="name"
                                            id="name"
                                            class="form-control"
                                            value="{{ old('name', $item->name ?? '') }}"
                                            required>
                                    </div>
                                </div>

                                {{-- Slug --}}
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label req">Slug</label>
                                        @error('slug') <span class="text-danger d-block">{{ $message }}</span> @enderror
                                        <input type="text"
                                            name="slug"
                                            id="slug"
                                            class="form-control"
                                            value="{{ old('slug', $item->slug ?? '') }}"
                                            required>
                                    </div>
                                </div>

                                {{-- Person Type --}}
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label req">Person Type</label>
                                        @error('person_type') <span class="text-danger d-block">{{ $message }}</span> @enderror
                                        @php $pt = old('person_type', $item->person_type ?? 'hero'); @endphp
                                        <select name="person_type" class="form-control select2" required>
                                            <option value="athlete" {{ $pt==='athlete' ? 'selected' : '' }}>Athlete</option>
                                            <option value="poet" {{ $pt==='poet' ? 'selected' : '' }}>Poet</option>
                                            <option value="hero" {{ $pt==='hero' ? 'selected' : '' }}>Hero</option>
                                            <option value="scholar" {{ $pt==='scholar' ? 'selected' : '' }}>Scholar</option>
                                            <option value="artist" {{ $pt==='artist' ? 'selected' : '' }}>Artist</option>
                                            <option value="other" {{ $pt==='other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            {{-- CATEGORY / SECTION / FEATURED / STATUS --}}
                            <div class="row">
                                {{-- Category --}}
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Category</label>
                                        @error('category_id') <span class="text-danger d-block">{{ $message }}</span> @enderror
                                        @php $catVal = (int) old('category_id', $item->category_id ?? 0); @endphp
                                        <select name="category_id" class="form-control select2">
                                            <option value="0" {{ $catVal===0 ? 'selected' : '' }}>None</option>
                                            @foreach($categories as $id => $name)
                                            <option value="{{ $id }}" {{ $catVal===(int)$id ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- Section 
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Section</label>
                                        @error('section_id') <span class="text-danger d-block">{{ $message }}</span> @enderror
                                @php $secVal = (int) old('section_id', $item->section_id ?? 0); @endphp
                                <select name="section_id" class="form-control select2">
                                    <option value="0" {{ $secVal===0 ? 'selected' : '' }}>None</option>
                                    @foreach($sections as $id => $name)
                                    <option value="{{ $id }}" {{ $secVal===(int)$id ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                    </div>--}}

                    {{-- Featured --}}
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="form-label">Featured</label>
                            @php $feat = old('is_featured', $item->is_featured ?? 'no'); @endphp
                            <select name="is_featured" class="form-control">
                                <option value="no" {{ $feat==='no' ? 'selected' : '' }}>No</option>
                                <option value="yes" {{ $feat==='yes' ? 'selected' : '' }}>Yes</option>
                            </select>
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="form-label">Status</label>
                            @php $status = old('status', $item->status ?? 'draft'); @endphp
                            <select name="status" class="form-control">
                                <option value="draft" {{ $status==='draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ $status==='published' ? 'selected' : '' }}>Published</option>
                                <option value="archived" {{ $status==='archived' ? 'selected' : '' }}>Archived</option>
                            </select>
                        </div>
                    </div>

                    {{-- Order --}}
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="form-label">Display Order</label>
                            @error('display_order') <span class="text-danger d-block">{{ $message }}</span> @enderror
                            <input type="number"
                                name="display_order"
                                class="form-control"
                                value="{{ old('display_order', $item->display_order ?? 0) }}">
                        </div>
                    </div>
                </div>

                {{-- PROFILE MEDIA --}}
                <div class="row mt-3">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Profile Image</label>
                            @error('profile_media_id') <span class="text-danger d-block">{{ $message }}</span> @enderror

                            <input type="hidden"
                                name="profile_media_id"
                                id="profile_media_id"
                                value="{{ old('profile_media_id', $item->media->uuid ?? '') }}">

                            <button type="button"
                                class="btn btn-outline-primary open-media-manager"
                                data-mode="single"
                                data-target-input="#profile_media_id"
                                data-preview-target="#profile_media_preview">
                                Choose Profile Image
                            </button>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <label class="form-label">Websites Optional</label>
                            @php
                            $selected = old('company_ids', $selectedCompanies ?? []);
                            @endphp

                            <select name="company_ids[]"
                                id="company_ids"
                                class="form-control select2"
                                multiple>
                                @foreach($companies as $id => $name)
                                <option value="{{ $id }}"
                                    {{ in_array((int)$id, array_map('intval', (array)$selected), true) ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                                @endforeach
                            </select>

                            <small class="text-muted">
                                Optional: select companies where this person should appear. Leave empty to not assign to any company.
                            </small>
                        </div>
                    </div>
                    {{-- PROFILE MEDIA  
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label class="form-label">Website</label>
                                        @error('website_url') <span class="text-danger d-block">{{ $message }}</span> @enderror
                    <input type="url"
                        name="website_url"
                        class="form-control"
                        value="{{ old('website_url', $item->website_url ?? '') }}"
                        placeholder="https://...">
                </div>
            </div>--}}
        </div>


        <div class="row mt-3">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="form-label">Details About Person</label>
                    <textarea name="description" id="body-editor" class="form-control" rows="30" placeholder="description">{{ old('description', $item->description ?? '') }}</textarea>
                </div>
            </div>
        </div>


        {{-- DATES / PLACES
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-label">Birth Date</label>
                                        @error('birth_date') <span class="text-danger d-block">{{ $message }}</span> @enderror
        <input type="date"
            name="birth_date"
            class="form-control"
            value="{{ old('birth_date', optional($item->birth_date)->format('Y-m-d')) }}">
    </div>
</div>

<div class="col-md-2">
    <div class="form-group">
        <label class="form-label">Death Date</label>
        @error('death_date') <span class="text-danger d-block">{{ $message }}</span> @enderror
        <input type="date"
            name="death_date"
            class="form-control"
            value="{{ old('death_date', optional($item->death_date)->format('Y-m-d')) }}">
    </div>
</div>

<div class="col-md-2">
    <div class="form-group">
        <label class="form-label">Gender</label>
        <input type="text"
            name="gender"
            class="form-control"
            value="{{ old('gender', $item->gender ?? '') }}">
    </div>
</div>

<div class="col-md-3">
    <div class="form-group">
        <label class="form-label">Birth Place</label>
        <input type="text"
            name="birth_place"
            class="form-control"
            value="{{ old('birth_place', $item->birth_place ?? '') }}">
    </div>
</div>

<div class="col-md-3">
    <div class="form-group">
        <label class="form-label">Nationality</label>
        <input type="text"
            name="nationality"
            class="form-control"
            value="{{ old('nationality', $item->nationality ?? '') }}">
    </div>
</div>
</div> --}}

{{-- FIELD / ERA 
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Primary Field</label>
                                        <input type="text"
                                            name="primary_field"
                                            class="form-control"
                                            value="{{ old('primary_field', $item->primary_field ?? '') }}"
placeholder="Cricket, Poetry, ...">
</div>
</div>

<div class="col-md-6">
    <div class="form-group">
        <label class="form-label">Era / Period</label>
        <input type="text"
            name="era_period"
            class="form-control"
            value="{{ old('era_period', $item->era_period ?? '') }}"
            placeholder="20th century, Mughal era, ...">
    </div>
</div>
</div>--}}

{{-- BIO
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Short Bio</label>
                                        <textarea name="short_bio"
                                            class="form-control"
                                            rows="2">{{ old('short_bio', $item->short_bio ?? '') }}</textarea>
</div>
</div>

<div class="col-md-12">
    <div class="form-group">
        <label class="form-label">Biography</label>
        <textarea name="biography"
            class="form-control"
            rows="4">{{ old('biography', $item->biography ?? '') }}</textarea>
    </div>
</div>
</div> --}}

{{-- SOCIAL / META (optional as JSON text) 
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Social Links (JSON)</label>
                                        <small class="text-muted d-block mb-1">Example: {"wikipedia":"...","x":"..."}</small>
                                        <textarea name="social_links"
                                            class="form-control"
                                            rows="3">{{ old('social_links', is_array($item->social_links ?? null) ? json_encode($item->social_links) : ($item->social_links ?? '')) }}</textarea>
</div>
</div>

<div class="col-md-6">
    <div class="form-group">
        <label class="form-label">Meta (JSON)</label>
        <small class="text-muted d-block mb-1">Type specific data (cricketer stats, poet pen_name, etc.)</small>
        <textarea name="meta"
            class="form-control"
            rows="3">{{ old('meta', is_array($item->meta ?? null) ? json_encode($item->meta) : ($item->meta ?? '')) }}</textarea>
    </div>
</div>
</div>--}}

{{-- BUTTONS --}}
<div class="row mt-3">
    <div class="col-12">
        <a href="{{ route('settings.people.list') }}" class="btn btn-warning">
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
                    removePlugins: 'exportpdf',
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

        let slugManuallyChanged = false;

        function slugify(text) {
            return text
                .toString()
                .toLowerCase()
                .trim()
                .replace(/[\s_]+/g, '-')
                .replace(/[^a-z0-9\-]+/g, '')
                .replace(/\-\-+/g, '-');
        }

        $('#slug').on('input', function() {
            slugManuallyChanged = true;
        });

        $('#name').on('input change', function() {
            if (!slugManuallyChanged) {
                $('#slug').val(slugify($(this).val()));
            }
        });
    });
</script>
@endpush