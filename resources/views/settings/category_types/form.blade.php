@extends('layouts.' . config('settings.active_layout'))

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            // Auto-generate slug from title
            $('#title').on('keyup blur', function() {
                const title = $(this).val();
                const slugField = $('#slug');

                if (slugField.val() === '' || slugField.data('auto-generated') === true) {
                    slugField.val(title.toLowerCase()
                        .trim()
                        .replace(/[^\w\s-]/g, '')
                        .replace(/[\s_-]+/g, '-')
                        .replace(/^-+|-+$/g, ''));
                    slugField.data('auto-generated', true);
                }
            });

            // Mark slug as manually edited if user changes it
            $('#slug').on('keyup', function() {
                $(this).data('auto-generated', false);
            });
        });
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
                            {{-- Additional header elements if needed --}}
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            @php
                                $actionUrl = $item->exists
                                    ? route('settings.category-types.update', $item->uuid)
                                    : route('settings.category-types.store');
                            @endphp

                            <form method="POST" action="{{ $actionUrl }}">
                                @csrf
                                @if($item->exists)
                                    @method('PUT')
                                @endif

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="title" class="form-label req">Title</label>
                                            <span class="help">
                                                @if(session()->has('errors'))
                                                    {!! session()->get('errors')->first('title') !!}
                                                @endif
                                            </span>
                                            <input type="text"
                                                name="title"
                                                id="title"
                                                class="form-control @error('title') is-invalid @enderror"
                                                value="{{ old('title', $item->title ?? '') }}"
                                                required
                                                placeholder="Enter category type title">
                                            @error('title')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="slug" class="form-label">Slug</label>
                                            <span class="help">
                                                @if(session()->has('errors'))
                                                    {!! session()->get('errors')->first('slug') !!}
                                                @endif
                                                <small class="text-muted">(Auto-generated from title if left empty)</small>
                                            </span>
                                            <input type="text"
                                                name="slug"
                                                id="slug"
                                                class="form-control @error('slug') is-invalid @enderror"
                                                value="{{ old('slug', $item->slug ?? '') }}"
                                                placeholder="category-type-slug">
                                            @error('slug')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="status" class="form-label req">Status</label>
                                            <span class="help">
                                                @if(session()->has('errors'))
                                                    {!! session()->get('errors')->first('status') !!}
                                                @endif
                                            </span>
                                            <select name="status"
                                                id="status"
                                                class="form-control @error('status') is-invalid @enderror"
                                                required>
                                                <option value="active" {{ old('status', $item->status ?? 'active') === 'active' ? 'selected' : '' }}>
                                                    Active
                                                </option>
                                                <option value="inactive" {{ old('status', $item->status ?? '') === 'inactive' ? 'selected' : '' }}>
                                                    Inactive
                                                </option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="sort_order" class="form-label">Sort Order</label>
                                            <span class="help">
                                                @if(session()->has('errors'))
                                                    {!! session()->get('errors')->first('sort_order') !!}
                                                @endif
                                                <small class="text-muted">(Lower numbers appear first)</small>
                                            </span>
                                            <input type="number"
                                                name="sort_order"
                                                id="sort_order"
                                                class="form-control @error('sort_order') is-invalid @enderror"
                                                value="{{ old('sort_order', $item->sort_order ?? '0') }}"
                                                min="0"
                                                step="1"
                                                placeholder="0">
                                            @error('sort_order')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <a href="{{ route('settings.category-types.list') }}" class="btn btn-warning">
                                            <i class="bx bx-arrow-back"></i> Back
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
