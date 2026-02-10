@extends('layouts.' . config('settings.active_layout'))

@push('stylesheets')
    <style>
        .cke_warning,
        .cke_notification.cke_notification_warning,
        .cke_notification[data-name="security-warning"],
        .cke_notification[data-name="version-warning"] {
            display: none !important;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header header-elements-inline">
                    <h5 class="card-title">{{ $title }}</h5>
                </div>
                <div class="card-body">
                    <form method="POST"
                        action="{{ $item->exists ? route('settings.faqs.update', $item->uuid) : route('settings.faqs.store') }}">
                        @csrf
                        @if ($item->exists)
                            @method('PUT')
                        @endif

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label req">Website</label>
                                    <select name="company_id" id="company_id" class="form-control select2" required>
                                        <option value="">Select Website</option>
                                        @foreach ($companies as $id => $name)
                                            <option value="{{ $id }}"
                                                {{ old('company_id', $item->company_id) == $id ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('company_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Category</label>
                                    <select name="category_id" id="category_id" class="form-control select2">
                                        <option value="">Select Category</option>
                                        @foreach ($categories ?? [] as $id => $name)
                                            <option value="{{ $id }}"
                                                {{ old('category_id', $item->category_id) == $id ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label req">Status</label>
                                    <select name="status" class="form-control" required>
                                        @foreach (['draft' => 'Draft', 'pending' => 'Pending', 'publish' => 'Publish'] as $key => $label)
                                            <option value="{{ $key }}"
                                                {{ old('status', $item->status ?? 'draft') === $key ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Slug</label>
                                    <input type="text" name="slug" class="form-control" id="slug"
                                        value="{{ old('slug', $item->slug) }}">
                                    <small class="text-muted">Auto-generated from question if left blank.</small>
                                    @error('slug')
                                        <small class="text-danger d-block">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Sort Order</label>
                                    <input type="number" name="sort_order" class="form-control"
                                        value="{{ old('sort_order', $item->sort_order) }}" min="0">
                                    <small class="text-muted">Lower numbers appear first.</small>
                                    @error('sort_order')
                                        <small class="text-danger d-block">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label req">Question</label>
                                    <input type="text" name="question" class="form-control" id="question"
                                        value="{{ old('question', $item->question) }}" required maxlength="500">
                                    @error('question')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label req">Answer</label>
                                    <textarea name="answer" id="answer-editor" class="form-control" rows="10" required>{{ old('answer', $item->answer) }}</textarea>
                                    @error('answer')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Meta Key</label>
                                    <input type="text" name="meta_key" class="form-control"
                                        value="{{ old('meta_key', $item->meta_key) }}">
                                    @error('meta_key')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Meta Value</label>
                                    <textarea name="meta_value" class="form-control" rows="2">{{ old('meta_value', $item->meta_value) }}</textarea>
                                    @error('meta_value')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <a href="{{ route('settings.faqs.list') }}" class="btn btn-secondary">
                                <i class="bx bx-arrow-back"></i> Back
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="bx bx-save"></i> Save
                            </button>
                        </div>
                    </form>
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
                if (typeof CKEDITOR !== 'undefined' && $('#answer-editor').length) {
                    CKEDITOR.replace('answer-editor', {
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

            // Slug auto-generation
            let slugChanged = false;
            $('#slug').on('input', function() {
                slugChanged = true;
            });
            $('#question').on('input', function() {
                if (!slugChanged) {
                    $('#slug').val($(this).val().toLowerCase().trim()
                        .replace(/[\s_]+/g, '-')
                        .replace(/[^a-z0-9-]/g, '')
                        .replace(/--+/g, '-'));
                }
            });
        });
    </script>
@endpush
