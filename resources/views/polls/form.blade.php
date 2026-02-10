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
    {{-- Global media manager --}}
    @include('components.media-manager', ['companies' => $companies ?? []])
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header header-elements-inline">
                    <h5 class="card-title">{{ $title ?? ($item->exists ? 'Edit Poll' : 'Create Poll') }}</h5>
                </div>
                <div class="card-body">
                    <form method="POST"
                        action="{{ $item->exists ? route('settings.polls.update', $item->uuid) : route('settings.polls.store') }}">
                        @csrf
                        @if ($item->exists)
                            @method('PUT')
                        @endif

                        <div class="row">
                            <div class="col-md-4">
                                @include('components.companies', [
                                    'companies' => $companies,
                                    'select_id' => 'company_id',
                                    'label' => 'Website',
                                    'selected_company_id' => old('company_id', $item->company_id),
                                ])
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
                                    <label class="form-label req">Title</label>
                                    <input type="text" name="title" class="form-control" id="title"
                                        value="{{ old('title', $item->title) }}" required>
                                    @error('title')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Slug</label>
                                    <input type="text" name="slug" class="form-control" id="slug"
                                        value="{{ old('slug', $item->slug) }}">
                                    <small class="text-muted">Auto-generated from title if left blank.</small>
                                    @error('slug')
                                        <small class="text-danger d-block">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label req">Status</label>
                                    <select name="status" class="form-control" required>
                                        @foreach (['draft' => 'Draft', 'active' => 'Active', 'closed' => 'Closed', 'archived' => 'Archived'] as $key => $label)
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
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label req">Poll Type</label>
                                    <select name="poll_type" class="form-control" required>
                                        @foreach (['single_choice' => 'Single Choice', 'multiple_choice' => 'Multiple Choice'] as $key => $label)
                                            <option value="{{ $key }}"
                                                {{ old('poll_type', $item->poll_type ?? 'single_choice') === $key ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('poll_type')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Start Date</label>
                                    <input type="datetime-local" name="start_date" class="form-control"
                                        value="{{ old('start_date', $item->start_date ? $item->start_date->format('Y-m-d\TH:i') : '') }}">
                                    @error('start_date')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">End Date</label>
                                    <input type="datetime-local" name="end_date" class="form-control"
                                        value="{{ old('end_date', $item->end_date ? $item->end_date->format('Y-m-d\TH:i') : '') }}">
                                    @error('end_date')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            {{-- Flags temporarily disabled
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Flags</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="allow_anonymous" id="allow_anonymous"
                                            value="1" {{ old('allow_anonymous', $item->allow_anonymous ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="allow_anonymous">
                                            Allow anonymous votes
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="allow_multiple_votes"
                                            id="allow_multiple_votes" value="1"
                                            {{ old('allow_multiple_votes', $item->allow_multiple_votes ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="allow_multiple_votes">
                                            Allow multiple votes per user/browser
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="show_results_immediately"
                                            id="show_results_immediately" value="1"
                                            {{ old('show_results_immediately', $item->show_results_immediately ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="show_results_immediately">
                                            Show results immediately after vote
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="show_results_before_voting"
                                            id="show_results_before_voting" value="1"
                                            {{ old('show_results_before_voting', $item->show_results_before_voting ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="show_results_before_voting">
                                            Show results before voting
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="show_results_after_close"
                                            id="show_results_after_close" value="1"
                                            {{ old('show_results_after_close', $item->show_results_after_close ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="show_results_after_close">
                                            Show results after poll closes
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="randomize_options"
                                            id="randomize_options" value="1"
                                            {{ old('randomize_options', $item->randomize_options ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="randomize_options">
                                            Randomize options per user
                                        </label>
                                    </div>
                                </div>
                            </div>
                            --}}
                        </div>

                        {{-- Description temporarily hidden
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Description</label>
                                    <textarea name="description" id="description" class="form-control" rows="4">{{ old('description', $item->description) }}</textarea>
                                    @error('description')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        --}}

                        {{-- Thumbnail --}}
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Thumbnail</label>
                                    <div class="input-group">
                                        <input type="hidden" name="thumbnail_id" id="thumbnail_id"
                                            value="{{ old('thumbnail_id', $item->thumbnail_id) }}">
                                        <button type="button" class="btn btn-outline-secondary open-media-manager"
                                            data-target-input="#thumbnail_id" data-preview-container="#thumbnail_preview">
                                            Select Thumbnail
                                        </button>
                                    </div>
                                    <div id="thumbnail_preview" class="mt-2">
                                        @if ($item->thumbnail)
                                            <img src="{{ $item->thumbnail->file_url }}" alt="Thumbnail" class="img-thumbnail"
                                                style="max-height: 120px;">
                                        @endif
                                    </div>
                                    @error('thumbnail_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr>

                        {{-- Poll Options --}}
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0">Poll Options</h6>
                                    <button type="button" class="btn btn-sm btn-primary" id="add-option-btn">
                                        <i class="bx bx-plus"></i> Add Option
                                    </button>
                                </div>
                                <div id="poll-options-container">
                                    @php
                                        $oldOptions = old('options', $item->options?->sortBy('display_order')->values()->toArray() ?? []);
                                    @endphp
                                    @forelse ($oldOptions as $index => $option)
                                        <div class="poll-option-item card mb-2" data-index="{{ $index }}">
                                            <div class="card-body">
                                                <div class="row align-items-end">
                                                    <div class="col-md-6">
                                                        <div class="form-group mb-2">
                                                            <label class="form-label req">Option Text</label>
                                                            <input type="text"
                                                                name="options[{{ $index }}][option_text]"
                                                                class="form-control"
                                                                value="{{ $option['option_text'] ?? '' }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group mb-2">
                                                            <label class="form-label">Display Order</label>
                                                            <input type="number"
                                                                name="options[{{ $index }}][display_order]"
                                                                class="form-control"
                                                                value="{{ $option['display_order'] ?? $index }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group mb-2">
                                                            <label class="form-label">Image</label>
                                                            <input type="hidden"
                                                                name="options[{{ $index }}][option_image_id]"
                                                                id="option_image_id_{{ $index }}"
                                                                value="{{ $option['option_image_id'] ?? '' }}">
                                                            <button type="button"
                                                                class="btn btn-outline-secondary btn-sm open-media-manager"
                                                                data-target-input="#option_image_id_{{ $index }}"
                                                                data-preview-target="#option_image_preview_{{ $index }}">
                                                                Select
                                                            </button>
                                                            <div id="option_image_preview_{{ $index }}" class="mt-1">
                                                                @if(isset($option['option_image_id']) && !empty($option['option_image_id']))
                                                                    @php
                                                                        $imageMedia = \App\Models\Web\Media::where('uuid', $option['option_image_id'])->first();
                                                                    @endphp
                                                                    @if($imageMedia)
                                                                        @php
                                                                            $url = $imageMedia->file_path ? asset('storage/' . $imageMedia->file_path) : ($imageMedia->external_url ?? '');
                                                                        @endphp
                                                                        @if($url)
                                                                            <img src="{{ $url }}" alt="{{ $imageMedia->title }}" class="img-thumbnail" style="max-width: 80px; max-height: 80px; object-fit: cover;">
                                                                        @endif
                                                                    @endif
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-1 text-end">
                                                        <button type="button"
                                                            class="btn btn-sm btn-danger remove-option-btn">
                                                            <i class="bx bx-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        {{-- At least two empty option rows by default --}}
                                        @for ($i = 0; $i < 2; $i++)
                                            <div class="poll-option-item card mb-2" data-index="{{ $i }}">
                                                <div class="card-body">
                                                    <div class="row align-items-end">
                                                        <div class="col-md-6">
                                                            <div class="form-group mb-2">
                                                                <label class="form-label req">Option Text</label>
                                                                <input type="text"
                                                                    name="options[{{ $i }}][option_text]"
                                                                    class="form-control" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group mb-2">
                                                                <label class="form-label">Display Order</label>
                                                                <input type="number"
                                                                    name="options[{{ $i }}][display_order]"
                                                                    class="form-control" value="{{ $i }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group mb-2">
                                                                <label class="form-label">Image</label>
                                                                <input type="hidden"
                                                                    name="options[{{ $i }}][option_image_id]"
                                                                    id="option_image_id_{{ $i }}">
                                                                <button type="button"
                                                                    class="btn btn-outline-secondary btn-sm open-media-manager"
                                                                    data-target-input="#option_image_id_{{ $i }}"
                                                                    data-preview-target="#option_image_preview_{{ $i }}">
                                                                    Select
                                                                </button>
                                                                <div id="option_image_preview_{{ $i }}" class="mt-1">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1 text-end">
                                                            <button type="button"
                                                                class="btn btn-sm btn-danger remove-option-btn">
                                                                <i class="bx bx-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endfor
                                    @endforelse
                                </div>
                                @error('options')
                                    <small class="text-danger d-block">{{ $message }}</small>
                                @enderror
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
                            <a href="{{ route('settings.polls.list') }}" class="btn btn-secondary">
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
        if (document.getElementById('description')) {
            CKEDITOR.replace('description', {
                removePlugins: 'about'
            });
        }

        $(document).ready(function() {
            let optionIndex = $('#poll-options-container .poll-option-item').length;

            $('#add-option-btn').on('click', function() {
                const index = optionIndex++;
                const html = `
                    <div class="poll-option-item card mb-2" data-index="${index}">
                        <div class="card-body">
                            <div class="row align-items-end">
                                <div class="col-md-6">
                                    <div class="form-group mb-2">
                                        <label class="form-label req">Option Text</label>
                                        <input type="text" name="options[${index}][option_text]" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group mb-2">
                                        <label class="form-label">Display Order</label>
                                        <input type="number" name="options[${index}][display_order]" class="form-control" value="${index}">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group mb-2">
                                        <label class="form-label">Image</label>
                                        <input type="hidden" name="options[${index}][option_image_id]" id="option_image_id_${index}">
                                        <button type="button" class="btn btn-outline-secondary btn-sm open-media-manager"
                                            data-target-input="#option_image_id_${index}"
                                            data-preview-container="#option_image_preview_${index}">
                                            Select
                                        </button>
                                        <div id="option_image_preview_${index}" class="mt-1"></div>
                                    </div>
                                </div>
                                <div class="col-md-1 text-end">
                                    <button type="button" class="btn btn-sm btn-danger remove-option-btn">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                $('#poll-options-container').append(html);
            });

            $(document).on('click', '.remove-option-btn', function() {
                $(this).closest('.poll-option-item').remove();
            });
        });
    </script>
@endpush
