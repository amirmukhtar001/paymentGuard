@extends('layouts.' . config('settings.active_layout'))

@section('content')
    {{-- Global media manager (WordPress-style) --}}
    @include('components.media-manager')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header header-elements-inline">
                    <h5 class="card-title">{{ $title }}</h5>
                </div>
                <div class="card-body">
                    <form method="POST"
                        action="{{ $item->exists ? route('settings.parties.update', $item->uuid) : route('settings.parties.store') }}">
                        @csrf
                        @if ($item->exists)
                            @method('PUT')
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label req">Status</label>
                                    <select name="status" class="form-control" required>
                                        @foreach (['active' => 'Active', 'inactive' => 'Inactive', 'draft' => 'Draft'] as $key => $label)
                                            <option value="{{ $key }}"
                                                {{ old('status', $item->status ?? 'active') === $key ? 'selected' : '' }}>
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
                                    <label class="form-label req">Short Name</label>
                                    <input type="text" name="short_name" class="form-control" id="short_name"
                                        value="{{ old('short_name', $item->short_name) }}" required placeholder="e.g. PML-N">
                                    @error('short_name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" name="full_name" class="form-control" id="full_name"
                                        value="{{ old('full_name', $item->full_name) }}" placeholder="e.g. Pakistan Muslim League (Nawaz)">
                                    @error('full_name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Logo / Image</label>
                                    @error('media_id')
                                        <span class="text-danger d-block">{{ $message }}</span>
                                    @enderror
                                    <input type="hidden" name="media_id" id="media_id" value="{{ old('media_id', $item->media->uuid ?? '') }}">
                                    <button type="button"
                                        class="btn btn-outline-primary open-media-manager"
                                        data-mode="single"
                                        data-target-input="#media_id"
                                        data-preview-target="#media_preview">
                                        Choose Logo
                                    </button>

                                    <div id="media_preview" class="mt-2">
                                        @php
                                            $mediaId = old('media_id', $item->media->uuid ?? null);
                                        @endphp

                                        @if($mediaId && $item->media)
                                            @php
                                                $url = $item->media->file_path ? asset('storage/' . $item->media->file_path) : ($item->media->external_url ?? null);
                                            @endphp
                                            @if($url)
                                                <div class="position-relative d-inline-block">
                                                    <img src="{{ $url }}"
                                                        alt="{{ $item->short_name }}"
                                                        class="img-thumbnail"
                                                        style="max-width: 150px; max-height: 150px; object-fit: cover;">
                                                    <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1" 
                                                            onclick="$('#media_id').val(''); $('#media_preview').html('<span class=\'text-muted\'>No logo selected</span>');" 
                                                            style="z-index: 10;">×</button>
                                                </div>
                                            @else
                                                <span class="text-muted">No media file found</span>
                                            @endif
                                        @else
                                            <span class="text-muted">No logo selected</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <a href="{{ route('settings.parties.index') }}" class="btn btn-secondary">
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
    <script>
        $(document).ready(function() {
            // Update preview after media manager modal closes
            $(document).on('hidden.bs.modal', '#mediaManagerModal', function() {
                setTimeout(function() {
                    updateMediaPreview();
                }, 200);
            });

            function updateMediaPreview() {
                const mediaId = $('#media_id').val();
                const previewContainer = $('#media_preview');

                if (!mediaId) {
                    previewContainer.html('<span class="text-muted">No logo selected</span>');
                    return;
                }

                $.ajax({
                    url: '{{ route('settings.media.images') }}',
                    type: 'GET',
                    data: {
                        uuid: mediaId
                    },
                    success: function(files) {
                        const file = Array.isArray(files) ? files[0] : files;
                        if (file && file.url) {
                            previewContainer.html(`
                                <div class="position-relative d-inline-block">
                                    <img src="${file.url}" alt="${file.title || ''}" class="img-thumbnail" style="max-width: 150px; max-height: 150px; object-fit: cover;">
                                    <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1" 
                                            onclick="$('#media_id').val(''); $('#media_preview').html('<span class=\'text-muted\'>No logo selected</span>');" 
                                            style="z-index: 10;">×</button>
                                </div>
                            `);
                        } else {
                            previewContainer.html('<span class="text-muted">No logo selected</span>');
                        }
                    },
                    error: function() {
                        previewContainer.html('<span class="text-danger">Error loading preview</span>');
                    }
                });
            }
        });
    </script>
@endpush
