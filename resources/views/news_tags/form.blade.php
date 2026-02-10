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
                                    ? route('settings.news_tags.update', $item->uuid)
                                    : route('settings.news_tags.store') }}">
                            @csrf
                            @if($item->exists)
                            @method('PUT')
                            @endif

                            <div class="row">
                                {{-- Company --}}
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label req">Department</label>
                                        <span class="help">
                                            @if($errors->has('company_id')) {!! $errors->first('company_id') !!} @endif
                                        </span>
                                        <select name="company_id"
                                            id="company_id"
                                            class="form-control select2"
                                            required>
                                            <option value="">Select Department</option>
                                            @foreach($companies as $id => $name)
                                            <option value="{{ $id }}"
                                                {{ old('company_id', $item->company_id ?? '') == $id ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- Name --}}
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label req">Name</label>
                                        <span class="help">
                                            @if($errors->has('name')) {!! $errors->first('name') !!} @endif
                                        </span>
                                        <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $item->name ?? '') }}"
                                            required>
                                    </div>
                                </div>
                            </div>

                            {{-- Slug --}}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label req">Slug</label>
                                        <span class="help">
                                            @if($errors->has('slug')) {!! $errors->first('slug') !!} @endif
                                        </span>
                                        <input type="text" id="slug" name="slug" class="form-control" value="{{ old('slug', $item->slug ?? '') }}"
                                            required>
                                    </div>
                                </div>
                            </div>

                            {{-- Buttons --}}
                            <div class="row mt-3">
                                <div class="col-12">
                                    <a href="{{ route('settings.news_tags.list') }}" class="btn btn-warning">
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
                .replace(/[\s_]+/g, '-') // spaces/underscores -> -
                .replace(/[^a-z0-9\-]+/g, '') // remove non-alphanumeric/hyphen
                .replace(/\-\-+/g, '-'); // collapse multiple -
        }

        $('#slug').on('input', function() {
            // User is typing directly into slug -> don't auto override anymore
            slugManuallyChanged = true;
        });

        $('#name').on('input change', function() {
            if (!slugManuallyChanged) {
                const name = $(this).val();
                $('#slug').val(slugify(name));
            }
        });
    });
</script>
@endpush