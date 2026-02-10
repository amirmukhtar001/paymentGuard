@extends('layouts.' . config('settings.active_layout'))

@push('scripts')
{{-- Add Category-specific JS here if ever needed --}}
@endpush

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
                            action="{{ $item->exists
                                    ? route('settings.categories.update', $item->uuid)
                                    : route('settings.categories.store') }}">
                            @csrf
                            @if($item->exists)
                            @method('PUT')
                            @endif

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="title" class="form-label req">
                                            {{ config('settings.category_title', 'Category') }} Name
                                        </label>
                                        <span class="help">
                                            @if(session()->has('errors'))
                                            {!! session()->get('errors')->first('title') !!}
                                            @endif
                                        </span>
                                        <input type="text"
                                            name="title"
                                            id="title"
                                            class="form-control"
                                            value="{{ old('title', $item->title ?? '') }}"
                                            required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="parent_id" class="form-label">
                                            Select Parent {{ config('settings.category_title', 'Category') }}
                                        </label>
                                        <span class="help">
                                            @if(Session::has('errors'))
                                            {!! Session::get('errors')->first('parent_id') !!}
                                            @endif
                                        </span>
                                        <select name="parent_id"
                                            id="parent_id"
                                            class="form-control select2">
                                            <option value="">
                                                This is a parent {{ config('settings.category_title', 'Category') }}
                                            </option>
                                            @foreach($categories_dd as $key => $categoryName)
                                            <option value="{{ $key }}"
                                                {{ old('parent_id', $item->parent_id ?? '') == $key ? 'selected' : '' }}>
                                                {{ $categoryName }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            {{-- Category Type & Sort Order & Status --}}
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="category_type_id" class="control-label req">
                                            Select Category Type
                                        </label>
                                        <span class="help">
                                            @if(Session::has('errors'))
                                            {!! Session::get('errors')->first('category_type_id') !!}
                                            @endif
                                        </span>
                                        <select name="category_type_id"
                                            id="category_type_id"
                                            class="form-control select2"
                                            required>
                                            <option value="">Select a Type</option>
                                            @foreach($category_types as $key => $type)
                                            <option value="{{ $key }}"
                                                {{ old('category_type_id', $item->category_type_id ?? '') == $key ? 'selected' : '' }}>
                                                {{ $type }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label req">Status</label>
                                        <span class="help">
                                            @if(Session::has('errors'))
                                            {!! Session::get('errors')->first('status') !!}
                                            @endif
                                        </span>
                                        @php
                                        $statusValue = old('status', $item->status ?? 'active');
                                        @endphp
                                        <select name="status"
                                            class="form-control"
                                            required>
                                            <option value="active" {{ $statusValue === 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="inactive" {{ $statusValue === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
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
                            </div>

                            {{-- Slug --}}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="slug" class="form-label">
                                            Slug (optional)
                                        </label>
                                        <span class="help">
                                            @if(Session::has('errors'))
                                            {!! Session::get('errors')->first('slug') !!}
                                            @endif
                                        </span>
                                        <input type="text"
                                            name="slug"
                                            id="slug"
                                            class="form-control"
                                            value="{{ old('slug', $item->slug ?? '') }}">
                                        <small class="text-muted">
                                            If left blank, slug will be generated from the title.
                                        </small>
                                    </div>
                                </div>
                            </div>

                            {{-- Description --}}
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="description" class="form-label">
                                            Description
                                        </label>
                                        <span class="help">
                                            @if(session()->has('errors'))
                                            {!! session()->get('errors')->first('description') !!}
                                            @endif
                                        </span>
                                        <textarea name="description" id="description" class="form-control" rows="4">{{ old('description', $item->description ?? '') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            {{-- Buttons --}}
                            <div class="row">
                                <div class="col-12">
                                    <a href="{{ route('settings.categories.list') }}"
                                        class="btn btn-warning"><i class="bx bx-arrow-back tf-icons"></i> Back</a>
                                    <button type="submit" class="btn btn-success"><i class="bx bx-save"></i> Save </button>
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
                .replace(/[\s\_]+/g, '-') // spaces/underscores -> -
                .replace(/[^a-z0-9\-]+/g, '') // remove non-alphanumeric/hyphen
                .replace(/\-\-+/g, '-'); // collapse multiple -
        }

        $('#slug').on('input', function() {
            // User is typing directly into slug -> don't auto override anymore
            slugManuallyChanged = true;
        });

        $('#title').on('input change', function() {
            if (!slugManuallyChanged) {
                const title = $(this).val();
                $('#slug').val(slugify(title));
            }
        });
    });
</script>
@endpush