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
                                        ? route('settings.sliders.update', $item->uuid)
                                        : route('settings.sliders.store') }}">
                            @csrf
                            @if($item->exists)
                            @method('PUT')
                            @endif

                            {{-- Company + Name + Slug --}}
                            <div class="row">
                                {{-- Company --}}
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
                                        <label class="form-label req">Name</label>
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

                            {{-- Description + Status + Sort / Transition / Autoplay --}}
                            <div class="row">
                                {{-- Description --}}
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label class="form-label">Description</label>
                                        <textarea name="description"
                                            class="form-control"
                                            rows="3">{{ old('description', $item->description ?? '') }}</textarea>
                                    </div>
                                </div>

                                {{-- Status + Sort / Transition / Autoplay --}}
                                <div class="col-md-4">
                                    <div class="row">
                                        {{-- Status --}}
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label req">Status</label>
                                                @php
                                                $statusValue = old('status', $item->status ?? 'active');
                                                @endphp
                                                <select name="status" class="form-control" required>
                                                    <option value="draft" {{ $statusValue === 'draft' ? 'selected' : '' }}>Draft</option>
                                                    <option value="active" {{ $statusValue === 'active' ? 'selected' : '' }}>Active</option>
                                                    <option value="inactive" {{ $statusValue === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                                    <option value="archived" {{ $statusValue === 'archived' ? 'selected' : '' }}>Archived</option>
                                                </select>
                                            </div>
                                        </div>

                                        {{-- Sort order --}}
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Sort Order</label>
                                                <input type="number"
                                                    name="sort_order"
                                                    class="form-control"
                                                    value="{{ old('sort_order', $item->sort_order ?? 0) }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Transition & Autoplay --}}
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Transition</label>
                                        <input type="text"
                                            name="transition"
                                            class="form-control"
                                            placeholder="e.g., fade, slide"
                                            value="{{ old('transition', $item->transition ?? '') }}">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Autoplay (ms)</label>
                                        <input type="number"
                                            name="autoplay_ms"
                                            class="form-control"
                                            placeholder="e.g., 5000"
                                            value="{{ old('autoplay_ms', $item->autoplay_ms ?? '') }}">
                                    </div>
                                </div>
                            </div>

                            {{-- Buttons --}}
                            <div class="row mt-3">
                                <div class="col-12">
                                    <a href="{{ route('settings.sliders.list') }}" class="btn btn-warning">
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