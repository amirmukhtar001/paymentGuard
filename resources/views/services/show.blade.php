@extends('layouts.' . config('settings.active_layout'))

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header header-elements-inline">
                    <h5 class="card-title">{{ $title }}</h5>
                    <div class="header-elements">
                        <a href="{{ route('settings.services.edit', $item->uuid) }}" class="btn btn-primary">
                            <i class="bx bx-edit"></i> Edit
                        </a>
                        <a href="{{ route('settings.services.list') }}" class="btn btn-secondary">
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
                                    <th>Department</th>
                                    <td>{{ optional($item->department)->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Category</th>
                                    <td>{{ $item->categories->pluck('title')->implode(', ') ?: '-' }}</td>
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

                    @if($attachedFilesMedia && $attachedFilesMedia->isNotEmpty())
                        <div class="row mt-3">
                            <div class="col-12">
                                <h6>Attached Files</h6>
                                <div class="list-group">
                                    @foreach($attachedFilesMedia as $file)
                                        <div class="list-group-item">
                                            <a href="{{ $file->file_path ? asset('storage/' . $file->file_path) : $file->external_url }}" target="_blank">
                                                {{ $file->title ?? $file->file_name }}
                                            </a>
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
