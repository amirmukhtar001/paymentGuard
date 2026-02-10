@extends('layouts.' . config('settings.active_layout'))

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header header-elements-inline">
                    <h5 class="card-title">{{ $title }}</h5>
                    <div class="header-elements">
                        <a href="{{ route('settings.feedbacks.list') }}" class="btn btn-secondary">
                            <i class="bx bx-arrow-back"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">Name</th>
                                    <td>{{ $item->name }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $item->email }}</td>
                                </tr>
                                <tr>
                                    <th>Department</th>
                                    <td>{{ optional($item->department)->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Created At</th>
                                    <td>{{ $item->created_at ? $item->created_at->format('d m Y') : '-' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">Updated At</th>
                                    <td>{{ $item->updated_at ? $item->updated_at->format('d m Y') : '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <h6>Feedback</h6>
                            <div class="border p-3 bg-light rounded">
                                {{ $item->feedback }}
                            </div>
                        </div>
                    </div>

                    @if($item->reply)
                        <div class="row mt-3">
                            <div class="col-12">
                                <h6>Reply</h6>
                                <div class="border p-3 rounded">
                                    {{ $item->reply }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
