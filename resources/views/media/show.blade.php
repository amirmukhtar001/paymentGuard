@extends('layouts.' . config('settings.active_layout'))

@section('content')
<div class="row">
    <div class="col-12">

        <div class="card">
            <div class="card-header header-elements-inline">
                <h5 class="card-title">{{ $title }}</h5>
                <div class="header-elements">
                    <a href="{{ route('settings.media.edit', $item->uuid) }}" class="btn btn-primary">
                        <i class="bx bx-edit"></i> Edit
                    </a>
                </div>
            </div>

            <div class="card-body">
                @if($item->kind === 'image' && $item->file_path && $item->storage_disk)
                <div class="mb-3">
                    <img src="{{ asset('storage/' . $item->file_path) }}"
                        alt="{{ $item->alt_text ?? $item->title }}"
                        style="max-width: 300px; height: auto;">
                </div>
                @endif

                <dl class="row">
                    <dt class="col-sm-3">Company</dt>
                    <dd class="col-sm-9">{{ optional($item->company)->title ?? '-' }}</dd>

                    <dt class="col-sm-3">Category</dt>
                    <dd class="col-sm-9">{{ optional($item->category)->name ?? '-' }}</dd>

                    <dt class="col-sm-3">Title</dt>
                    <dd class="col-sm-9">{{ $item->title ?? '-' }}</dd>

                    <dt class="col-sm-3">Kind</dt>
                    <dd class="col-sm-9">{{ ucfirst($item->kind) }}</dd>

                    <dt class="col-sm-3">Status</dt>
                    <dd class="col-sm-9">{{ ucfirst($item->status) }}</dd>

                    <dt class="col-sm-3">File</dt>
                    <dd class="col-sm-9">
                        @if($item->file_path && $item->storage_disk)
                        <a href="{{ asset('storage/' . $item->file_path) }}" target="_blank">
                            {{ $item->file_name ?? basename($item->file_path) }}
                        </a>
                        @else
                        -
                        @endif
                    </dd>

                    <dt class="col-sm-3">External URL</dt>
                    <dd class="col-sm-9">
                        @if($item->external_url)
                        <a href="{{ $item->external_url }}" target="_blank">{{ $item->external_url }}</a>
                        @else
                        -
                        @endif
                    </dd>

                    <dt class="col-sm-3">Description</dt>
                    <dd class="col-sm-9">{{ $item->description ?? '-' }}</dd>

                    <dt class="col-sm-3">Created At</dt>
                    <dd class="col-sm-9">{{ $item->created_at }}</dd>

                    <dt class="col-sm-3">Updated At</dt>
                    <dd class="col-sm-9">{{ $item->updated_at }}</dd>
                </dl>

                <a href="{{ route('settings.media.list') }}" class="btn btn-secondary">
                    <i class="bx bx-arrow-back"></i> Back to list
                </a>
            </div>

        </div>

    </div>
</div>
@endsection