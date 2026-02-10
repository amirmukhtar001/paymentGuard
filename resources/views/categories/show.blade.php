@extends('layouts.' . config('settings.active_layout'))

@section('content')

<div class="row">
    <div class="col-12">

        <div class="card">
            <div class="card-header header-elements-inline">
                <h5 class="card-title">{{ $title }}</h5>
                <div class="header-elements">
                    <a href="{{ route('settings.categories.edit', $item->uuid) }}" class="btn btn-primary">
                        <i class="bx bx-edit"></i> Edit
                    </a>
                </div>
            </div>

            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-3">UUID</dt>
                    <dd class="col-sm-9">{{ $item->uuid }}</dd>

                    <dt class="col-sm-3">Title</dt>
                    <dd class="col-sm-9">{{ $item->title }}</dd>

                    <dt class="col-sm-3">Slug</dt>
                    <dd class="col-sm-9">{{ $item->slug }}</dd>

                    <dt class="col-sm-3">Parent</dt>
                    <dd class="col-sm-9">{{ optional($item->parent)->title ?? '-' }}</dd>

                    <dt class="col-sm-3">Type</dt>
                    <dd class="col-sm-9">{{ optional($item->type)->name ?? '-' }}</dd>

                    <dt class="col-sm-3">Status</dt>
                    <dd class="col-sm-9">{{ ucfirst($item->status) }}</dd>

                    <dt class="col-sm-3">Sort Order</dt>
                    <dd class="col-sm-9">{{ $item->sort_order }}</dd>

                    <dt class="col-sm-3">Description</dt>
                    <dd class="col-sm-9">{{ $item->description }}</dd>

                    <dt class="col-sm-3">Created At</dt>
                    <dd class="col-sm-9">{{ $item->created_at }}</dd>

                    <dt class="col-sm-3">Updated At</dt>
                    <dd class="col-sm-9">{{ $item->updated_at }}</dd>
                </dl>

                <a href="{{ route('settings.categories.list') }}" class="btn btn-secondary">
                    <i class="bx bx-arrow-back"></i> Back to list
                </a>
            </div>

        </div>

    </div>
</div>

@endsection