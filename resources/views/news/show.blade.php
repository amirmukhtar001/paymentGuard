@extends('layouts.' . config('settings.active_layout'))

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">{{ $title }}</h5>
                    <div>
                        <a href="{{ route('settings.news.edit', $item->uuid) }}" class="btn btn-primary">
                            <i class="bx bx-edit"></i> Edit
                        </a>
                        <a href="{{ route('settings.news.list') }}" class="btn btn-secondary">
                            <i class="bx bx-arrow-back"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-3">Title</dt>
                        <dd class="col-sm-9">{{ $item->title }}</dd>

                        <dt class="col-sm-3">Company</dt>
                        <dd class="col-sm-9">{{ optional($item->company)->title ?? '-' }}</dd>

                        <dt class="col-sm-3">Status</dt>
                        <dd class="col-sm-9">{{ ucfirst($item->status) }}</dd>

                        <dt class="col-sm-3">Featured</dt>
                        <dd class="col-sm-9">{{ $item->is_featured ? 'Yes' : 'No' }}</dd>

                        <dt class="col-sm-3">Published At</dt>
                        <dd class="col-sm-9">{{ optional($item->published_at)->format('d M Y H:i') ?? '-' }}</dd>

                        <dt class="col-sm-3">Expires At</dt>
                        <dd class="col-sm-9">{{ optional($item->expires_at)->format('d M Y H:i') ?? '-' }}</dd>

                        <dt class="col-sm-3">Meta</dt>
                        <dd class="col-sm-9">
                            <ul class="list-unstyled mb-0">
                                <li><strong>Title:</strong> {{ $item->meta['title'] ?? '-' }}</li>
                                <li><strong>Keywords:</strong> {{ $item->meta['keywords'] ?? '-' }}</li>
                                <li><strong>Description:</strong> {{ $item->meta['description'] ?? '-' }}</li>
                            </ul>
                        </dd>

                        <dt class="col-sm-3">Summary</dt>
                        <dd class="col-sm-9">{{ $item->summary ?? '-' }}</dd>

                        <dt class="col-sm-3">Body</dt>
                        <dd class="col-sm-9">{!! $item->body !!}</dd>

                        <dt class="col-sm-3">Media</dt>
                        <dd class="col-sm-9">
                            @if ($item->media_type === 'image' && $item->image_path)
                                <img src="{{ asset('storage/' . $item->image_path) }}" class="img-fluid rounded" alt="News image">
                            @elseif ($item->media_type === 'video' && $item->video_url)
                                <a href="{{ $item->video_url }}" target="_blank" rel="noopener">View Video</a>
                            @else
                                -
                            @endif
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
@endsection

