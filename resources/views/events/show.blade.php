@extends('layouts.' . config('settings.active_layout'))

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header header-elements-inline">
                    <h5 class="card-title">{{ $title }}</h5>
                    <div class="header-elements">
                        <a href="{{ route('settings.events.edit', $item->uuid) }}" class="btn btn-primary">
                            <i class="bx bx-edit"></i> Edit
                        </a>
                        <a href="{{ route('settings.events.list') }}" class="btn btn-secondary">
                            <i class="bx bx-arrow-back"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">Title</th>
                                    <td>{{ $item->title }}</td>
                                </tr>
                                <tr>
                                    <th>Website</th>
                                    <td>{{ optional($item->company)->title ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Category</th>
                                    <td>{{ optional($item->category)->title ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <span class="badge bg-label-{{ $item->status === 'publish' ? 'success' : ($item->status === 'pending' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst($item->status) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Slug</th>
                                    <td>{{ $item->slug ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Event Date</th>
                                    <td>{{ $item->event_date ? $item->event_date->format('Y-m-d H:i') : '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Event End Date</th>
                                    <td>{{ $item->event_end_date ? $item->event_end_date->format('Y-m-d H:i') : '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Location</th>
                                    <td>{{ $item->location ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Video URL</th>
                                    <td>
                                        @if($item->video_url)
                                            <a href="{{ $item->video_url }}" target="_blank">{{ $item->video_url }}</a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Views</th>
                                    <td>{{ $item->views ?? 0 }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">Thumbnail</th>
                                    <td>
                                        @if($item->thumbnail)
                                            @php
                                                $url = $item->thumbnail->external_url ?? ($item->thumbnail->file_path ? asset('storage/' . $item->thumbnail->file_path) : null);
                                            @endphp
                                            @if($url)
                                                <img src="{{ $url }}" alt="{{ $item->thumbnail->title }}" class="img-thumbnail" style="max-width: 200px;">
                                            @else
                                                -
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Attached File</th>
                                    <td>
                                        @if($attachedFilesMedia->isNotEmpty())
                                            @php
                                                $file = $attachedFilesMedia->first();
                                                $fileUrl = $file->file_path ? asset('storage/' . $file->file_path) : ($file->external_url ?? null);
                                            @endphp
                                            @if($fileUrl)
                                                <a href="{{ $fileUrl }}" target="_blank">
                                                    {{ $file->title ?? $file->file_name }}
                                                </a>
                                            @else
                                                {{ $file->title ?? $file->file_name }}
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Meta Key</th>
                                    <td>{{ $item->meta_key ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Meta Value</th>
                                    <td>{{ $item->meta_value ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($item->summary)
                        <div class="row mt-3">
                            <div class="col-12">
                                <h6>Summary</h6>
                                <div class="border p-3">
                                    {{ $item->summary }}
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($galleryImagesMedia && $galleryImagesMedia->isNotEmpty())
                        <div class="row mt-3">
                            <div class="col-12">
                                <h6>Event Gallery Images</h6>
                                <div class="row g-2">
                                    @foreach($galleryImagesMedia as $image)
                                        <div class="col-md-3">
                                            @php
                                                $url = $image->file_path ? asset('storage/' . $image->file_path) : ($image->external_url ?? '');
                                            @endphp
                                            @if($url)
                                                <img src="{{ $url }}" alt="{{ $image->title }}" class="img-thumbnail w-100" style="height: 200px; object-fit: cover;">
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($item->galleries && $item->galleries->isNotEmpty())
                        <div class="row mt-3">
                            <div class="col-12">
                                <h6>Related Galleries</h6>
                                <div class="list-group">
                                    @foreach($item->galleries as $gallery)
                                        <div class="list-group-item">
                                            <strong>{{ $gallery->name }}</strong>
                                            @if($gallery->description)
                                                <br><small class="text-muted">{{ $gallery->description }}</small>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="row mt-3">
                        <div class="col-12">
                            <h6>Body</h6>
                            <div class="border p-3">
                                {!! $item->body !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
