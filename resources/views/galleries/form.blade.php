@extends('layouts.' . config('settings.active_layout'))
@include('components.media-manager', ['companies' => $companies])
@section('content')

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
                            action="{{ $item->exists ? route('settings.galleries.update', $item->uuid) : route('settings.galleries.store') }}">
                            @csrf
                            @if($item->exists)
                            @method('PUT')
                            @endif

                            {{-- Website --}}
                            <div class="row">
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

                                {{-- Name --}}
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label req">Gallery Title</label>
                                        <span class="help">
                                            @if($errors->has('name')) {!! $errors->first('name') !!} @endif
                                        </span>
                                        <input type="text"
                                            name="name"
                                            id="name"
                                            class="form-control"
                                            value="{{ old('name', $item->name ?? '') }}"
                                            required>
                                    </div>
                                </div>
                                {{-- Category --}}
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Gallery Category</label>
                                        <span class="help">
                                            @if($errors->has('category_id')) {!! $errors->first('category_id') !!} @endif
                                        </span>

                                        @php
                                        $catVal = (int) old('category_id', $item->category_id ?? ($categoryId ?? 0));
                                        @endphp

                                        @include('components.categories', [
                                        'categories' => $categories,
                                        'select_id' => 'category_id',
                                        'label' => 'Category',
                                        'selected' => $catVal,
                                        'placeholder' => 'Select Category',
                                        'disabled' => isset($categoryId) && empty($item->id), // optional: lock only on create
                                        ])



                                    </div>
                                </div>


                            </div>

                            {{-- Description --}}
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-label req">Status</label>
                                        @php
                                        $statusValue = old('status', $item->status ?? 'draft');
                                        @endphp
                                        <select name="status" class="form-control" required>
                                            <option value="draft" {{ $statusValue === 'draft' ? 'selected' : '' }}>Draft</option>
                                            <option value="published" {{ $statusValue === 'published' ? 'selected' : '' }}>Published</option>
                                            <option value="archived" {{ $statusValue === 'archived' ? 'selected' : '' }}>Archived</option>
                                        </select>
                                    </div>
                                </div>


                                {{-- Featured --}}
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-label">Show on home page</label>
                                        <span class="help">
                                            @if($errors->has('is_featured')) {!! $errors->first('is_featured') !!} @endif
                                        </span>
                                        @php $featuredVal = old('is_featured', $item->is_featured ?? 'no'); @endphp
                                        <select name="is_featured" class="form-control">
                                            <option value="no" {{ $featuredVal === 'no' ? 'selected' : '' }}>No</option>
                                            <option value="yes" {{ $featuredVal === 'yes' ? 'selected' : '' }}>Yes</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- Section --}}
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Optional Section</label>
                                        <span class="help">
                                            @if($errors->has('section_id')) {!! $errors->first('section_id') !!} @endif
                                        </span>

                                        @php $catVal = (int) old('section_id', $item->section_id ?? 0); @endphp
                                        @include('components.sections', [
                                        'sections' => $sections,
                                        'select_id' => 'section_id',
                                        'label' => 'Section',
                                        'selected' => $item->section_id ?? 0,
                                        'placeholder' => 'Select Section'
                                        ])


                                    </div>
                                </div>

                                {{-- Slug --}}
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Slug</label>
                                        <span class="help">
                                            @if($errors->has('slug')) {!! $errors->first('slug') !!} @endif
                                        </span>
                                        <input type="text"
                                            name="slug"
                                            id="slug"
                                            class="form-control"
                                            value="{{ old('slug', $item->slug ?? '') }}">
                                    </div>
                                </div>
                            </div>
                            {{-- Featured / Category / URL --}}
                            <div class="row">
                                {{-- Type & Status --}}
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-label req">Type</label>
                                        @php
                                        $typeValue = old('type', $item->type ?? 'image');
                                        @endphp
                                        <select name="type" class="form-control" required>
                                            <option value="image" {{ $typeValue === 'image' ? 'selected' : '' }}>Image</option>
                                            <option value="video" {{ $typeValue === 'video' ? 'selected' : '' }}>Video</option>
                                            <option value="mixed" {{ $typeValue === 'mixed' ? 'selected' : '' }}>Media Url</option>
                                        </select>
                                    </div>
                                </div>
                                {{-- Media URL (show only when type=mixed) --}}
                                <div class="col-md-5" id="media_url_wrap" style="display:none;">
                                    <div class="form-group">
                                        <label class="form-label">Media URL</label>
                                        <span class="help">
                                            @if($errors->has('media_url')) {!! $errors->first('media_url') !!} @endif
                                        </span>
                                        <input type="url" name="media_url" id="media_url" class="form-control" value="{{ old('media_url', $item->media_url ?? '') }}" placeholder="https://example.com/..." />
                                    </div>
                                </div>
                                <div class="col-md-4 mt-3" id="media_upload_wrap">
                                    <div class="form-group">
                                        <label class="form-label">Media File</label>
                                        {{-- hidden input to store selected media uuid --}}
                                        <input type="hidden" name="media_id" id="member_media_id" value="{{ old('media_id', $item->media->uuid ?? '') }}">

                                        <button type="button" class="btn btn-outline-primary open-media-manager" data-mode="single"
                                            data-target-input="#member_media_id" data-preview-target="#member_media_preview"> Choose Profile Image
                                        </button>
                                        {{-- Preview container --}}
                                        <div id="member_media_preview" class="mt-2">
                                            @php
                                            $mediaId = old('media_id', $item->media->uuid ?? null);
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
                                                <img src="{{ $url }}" alt="{{ $item->media->title }}" class="img-thumbnail" style="max-width: 50px; max-height: 50px; object-fit: cover;">

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
                            </div>
                            <div class="row">

                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label class="form-label">Description</label>
                                        <textarea name="description"
                                            class="form-control"
                                            rows="3">{{ old('description', $item->description ?? '') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            {{-- Buttons --}}
                            <div class="row mt-3">
                                <div class="col-12">
                                    <a href="{{ route('settings.galleries.list') }}" class="btn btn-warning">
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
<script>
    $(document).ready(function() {
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

        function toggleMixedFields() {
            const type = $('select[name="type"]').val();

            if (type === 'mixed') {
                $('#media_url_wrap').show();
                $('#media_upload_wrap').hide();

                // clear selected media if switching to mixed
                $('#member_media_id').val('');
                $('#member_media_preview').html('<span class="text-muted">No media selected</span>');
            } else {
                $('#media_url_wrap').hide();
                $('#media_upload_wrap').show();

                // clear url if switching away from mixed
                $('#media_url').val('');
            }
        }

        $('#slug').on('input', function() {
            slugManuallyChanged = true;
        });

        $('#name').on('input change', function() {
            if (!slugManuallyChanged) {
                const name = $(this).val();
                $('#slug').val(slugify(name));
            }
        });

        $('select[name="type"]').on('change', toggleMixedFields);

        // initial state
        toggleMixedFields();
    });
</script>
@endpush