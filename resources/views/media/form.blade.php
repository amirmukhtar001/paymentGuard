@extends('layouts.' . config('settings.active_layout'))

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
                                    ? route('settings.media.update', $item->uuid)
                                    : route('settings.media.store') }}"
                            enctype="multipart/form-data">
                            @csrf
                            @if($item->exists)
                            @method('PUT')
                            @endif

                            {{-- Row 1: Company & Category --}}
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label req">Website</label>
                                        <span class="help">
                                            @if($errors->has('company_id')) {!! $errors->first('company_id') !!} @endif
                                        </span>
                                        <select name="company_id"
                                            id="company_id"
                                            class="form-control select2"
                                            required>
                                            <option value="">Select Website</option>
                                            @foreach($companies as $id => $name)
                                            <option value="{{ $id }}"
                                                {{ old('company_id', $item->company_id ?? '') == $id ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Category</label>
                                        <span class="help">
                                            @if($errors->has('category_id')) {!! $errors->first('category_id') !!} @endif
                                        </span>
                                        <select name="category_id"
                                            id="category_id"
                                            class="form-control select2">
                                            <option value="">Select Category</option>
                                            @foreach($categories as $id => $name)
                                            <option value="{{ $id }}"
                                                {{ old('category_id', $item->category_id ?? '') == $id ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Title</label>
                                        <input type="text"
                                            name="title"
                                            class="form-control"
                                            value="{{ old('title', $item->title ?? '') }}">
                                    </div>
                                </div>

                                <!-- <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Collection Name</label>
                                        <input type="text"
                                            name="collection_name"
                                            class="form-control"
                                            value="{{ old('collection_name', $item->collection_name ?? '') }}">
                                    </div>
                                </div> -->
                            </div>

                            {{-- Row 2: Title & Kind & Status --}}
                            <div class="row">

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label req">Kind</label>
                                        @php
                                        $kindValue = old('kind', $item->kind ?? 'image');
                                        @endphp
                                        <select name="kind" class="form-control" required>
                                            <option value="image" {{ $kindValue === 'image' ? 'selected' : '' }}>Image</option>
                                            <option value="video" {{ $kindValue === 'video' ? 'selected' : '' }}>Video</option>
                                            <option value="audio" {{ $kindValue === 'audio' ? 'selected' : '' }}>Audio</option>
                                            <option value="document" {{ $kindValue === 'document' ? 'selected' : '' }}>Document</option>
                                            <option value="other" {{ $kindValue === 'other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-label req">Status</label>
                                        @php
                                        $statusValue = old('status', $item->status ?? 'active');
                                        @endphp
                                        <select name="status" class="form-control" required>
                                            <option value="draft" {{ $statusValue === 'draft' ? 'selected' : '' }}>Draft</option>
                                            <option value="active" {{ $statusValue === 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="archived" {{ $statusValue === 'archived' ? 'selected' : '' }}>Archived</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="form-label">Alt Text</label>
                                        <input type="text"
                                            name="alt_text"
                                            class="form-control"
                                            value="{{ old('alt_text', $item->alt_text ?? '') }}">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-label">Checked?</label>
                                        <div class="form-check mt-2">
                                            <input type="checkbox"
                                                name="checked"
                                                id="checked"
                                                class="form-check-input"
                                                value="1"
                                                {{ old('checked', $item->checked ?? false) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="checked">
                                                Mark as verified
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Row 3: Description & Alt Text --}}
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

                            {{-- Row 4: File & External URL --}}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">{{ $item->exists ? 'Replace File' : 'File' }}</label>
                                        <input type="file"
                                            name="file"
                                            class="form-control mt-2">
                                        @if($item->exists && $item->file_path && $item->storage_disk)
                                        <small class="form-text text-muted ">
                                            Current file:
                                            @php
                                            if ($item->kind === 'image' && $item->file_path && $item->storage_disk) {
                                            $url = asset('storage/'.$item->file_path);
                                            // return '<img src="'.e($url).'" alt="'.e($item->alt_text ?? $item->title).'" style="height:40px;width:auto;">';
                                            }
                                            @endphp
                                            <img src="{{ e($url) }}" alt="{{ e($item->alt_text ?? $item->title) }}" style="height:40px;width:auto;">
                                        </small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">External URL</label>
                                        <input type="url"
                                            name="external_url"
                                            class="form-control"
                                            value="{{ old('external_url', $item->external_url ?? '') }}">
                                    </div>
                                </div>
                            </div>

                            {{-- Row 5: Sort Order --}}
                            <div class="row mb-4">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Sort Order</label>
                                        <input type="number"
                                            name="sort_order"
                                            class="form-control"
                                            value="{{ old('sort_order', $item->sort_order ?? 0) }}">
                                    </div>
                                </div>
                            </div>

                            {{-- Buttons --}}
                            <div class="row">
                                <div class="col-12">
                                    <a href="{{ route('settings.media.list') }}" class="btn btn-warning">
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
