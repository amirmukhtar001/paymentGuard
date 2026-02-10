@extends('layouts.' . config('settings.active_layout'))

@section('content')
<div class="row">
    <div class="col-12">

        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Notification Details</h5>
            </div>

            <div class="card-body">

                <table class="table table-bordered">
                    <tr>
                        <th>Title</th>
                        <td>{{ $notification->title }}</td>
                    </tr>

                    <tr>
                        <th>Slug</th>
                        <td>{{ $notification->slug }}</td>
                    </tr>

                    <tr>
                        <th>Date</th>
                        <td>{{ optional($notification->notification_date)->format('Y-m-d') }}</td>
                    </tr>

                    <tr>
                        <th>Status</th>
                        <td>{{ ucfirst($notification->status->value ?? $notification->status) }}</td>
                    </tr>

                    <tr>
                        <th>Category</th>
                        <td>{{ optional($notification->category)->name ?? '-' }}</td>
                    </tr>

                    <tr>
                        <th>Department</th>
                        <td>{{ optional($notification->department)->name ?? '-' }}</td>
                    </tr>

                    <tr>
                        <th>Web Site</th>
                        <td>{{ optional($notification->company)->name ?? '-' }}</td>
                    </tr>

                    <tr>
                        <th>Document</th>
                        <td>
                            @if($notification->media)
                            <a href="{{ asset('storage/'.$notification->media->file_path) }}" target="_blank">
                                View Document
                            </a>
                            @else
                            -
                            @endif
                        </td>
                    </tr>
                </table>

                <a href="{{ route('settings.department-notifications.index') }}" class="btn btn-warning">
                    Back
                </a>

                <a href="{{ route('settings.department-notifications.edit', $notification->uuid) }}"
                    class="btn btn-primary">
                    Edit
                </a>

            </div>
        </div>

    </div>
</div>
@endsection