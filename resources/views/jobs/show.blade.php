@extends('layouts.' . config('settings.active_layout'))

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header header-elements-inline">
                    <h5 class="card-title">{{ $title }}</h5>
                    <div class="header-elements">
                        <a href="{{ route('settings.jobs.edit', $item->uuid) }}" class="btn btn-primary">
                            <i class="bx bx-edit"></i> Edit
                        </a>
                        <a href="{{ route('settings.jobs.list') }}" class="btn btn-secondary">
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
                                    <th>Job Type</th>
                                    <td>
                                        <span class="badge bg-label-primary">{{ ucfirst($item->job_type) }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Scale</th>
                                    <td>{{ $item->scale ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Vacancies</th>
                                    <td>{{ $item->vacancies ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Expiry Date</th>
                                    <td>{{ optional($item->expiry_date)->format('Y-m-d H:i') ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">Experience</th>
                                    <td>{{ $item->experience ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Age Limit</th>
                                    <td>{{ $item->age_limit ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Experience Field</th>
                                    <td>{{ $item->experience_field ?? '-' }}</td>
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
                                    <th>Thumbnail</th>
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
                                    <th>Views</th>
                                    <td>{{ $item->views ?? 0 }}</td>
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

                    @if($item->attached_files && count($item->attached_files) > 0)
                        <div class="row mt-3">
                            <div class="col-12">
                                <h6>Attached Files</h6>
                                <div class="list-group">
                                    @foreach($item->attached_files as $fileUuid)
                                        @php
                                            $file = \App\Models\Web\Media::where('uuid', $fileUuid)->first();
                                        @endphp
                                        @if($file)
                                            <div class="list-group-item">
                                                <a href="{{ $file->file_path ? asset('storage/' . $file->file_path) : $file->external_url }}" target="_blank">
                                                    {{ $file->title ?? $file->file_name }}
                                                </a>
                                            </div>
                                        @endif
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
