@extends('layouts.' . config('settings.active_layout'))

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">{{ $title }}</h5>
                    <div>
                        <a href="{{ route('settings.pages.edit', $item->uuid) }}" class="btn btn-primary">
                            <i class="bx bx-edit"></i> Edit
                        </a>
                        <a href="{{ route('settings.pages.list') }}" class="btn btn-secondary">
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

                        <dt class="col-sm-3">Url</dt>
                        <dd class="col-sm-9">{{ 'detail-page/' }}{{ optional($item)->slug ?? '-' }}</dd>

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

                        @if($item->relatedLinks && $item->relatedLinks->count() > 0)
                            <dt class="col-sm-3">Related Links</dt>
                            <dd class="col-sm-9">
                                <ul class="list-unstyled">
                                    @foreach($item->relatedLinks as $link)
                                        <li class="mb-2">
                                            <a href="{{ $link->url }}" target="_blank" rel="noopener" class="d-inline-flex align-items-center">
                                                <i class="bx bx-link-external me-2"></i>
                                                {{ $link->title }}
                                            </a>
                                            @if($link->sort_order)
                                                <small class="text-muted ms-2">(Order: {{ $link->sort_order }})</small>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </dd>
                        @endif

                        <dt class="col-sm-3">Media</dt>
                        <dd class="col-sm-9">
                            @if ($item->media_type === 'image' && $item->featuredImage)
                                @php
                                    $url = $item->featuredImage->external_url ?? ($item->featuredImage->file_path ? asset('storage/' . $item->featuredImage->file_path) : null);
                                @endphp
                                @if($url)
                                    <img src="{{ $url }}" class="img-fluid rounded" alt="{{ $item->featuredImage->title ?? 'Page image' }}">
                                @else
                                    -
                                @endif
                            @elseif ($item->media_type === 'video' && $item->video_url)
                                <div>
                                    <a href="{{ $item->video_url }}" target="_blank" rel="noopener" class="d-inline-block mb-2">View Video</a>
                                    @if($item->videoThumbnail)
                                        @php
                                            $thumbnailUrl = $item->videoThumbnail->external_url ?? ($item->videoThumbnail->file_path ? asset('storage/' . $item->videoThumbnail->file_path) : null);
                                        @endphp
                                        @if($thumbnailUrl)
                                            <div class="mt-2">
                                                <strong>Video Thumbnail:</strong><br>
                                                <img src="{{ $thumbnailUrl }}" class="img-fluid rounded" alt="{{ $item->videoThumbnail->title ?? 'Video thumbnail' }}" style="max-width: 300px;">
                                            </div>
                                        @endif
                                    @endif
                                </div>
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
