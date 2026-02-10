@extends('layouts.' . config('settings.active_layout'))

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header header-elements-inline">
                    <h5 class="card-title">{{ $title }}</h5>
                    <div class="header-elements">
                        <a href="{{ route('settings.faqs.edit', $item->uuid) }}" class="btn btn-primary">
                            <i class="bx bx-edit"></i> Edit
                        </a>
                        <a href="{{ route('settings.faqs.list') }}" class="btn btn-secondary">
                            <i class="bx bx-arrow-back"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">Question</th>
                                    <td>{{ $item->question }}</td>
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
                                    <th>Sort Order</th>
                                    <td>{{ $item->sort_order ?? '-' }}</td>
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
                                    <th width="30%">Meta Key</th>
                                    <td>{{ $item->meta_key ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Meta Value</th>
                                    <td>{{ $item->meta_value ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Created At</th>
                                    <td>{{ $item->created_at ? $item->created_at->format('Y-m-d H:i:s') : '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Updated At</th>
                                    <td>{{ $item->updated_at ? $item->updated_at->format('Y-m-d H:i:s') : '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <h6>Answer</h6>
                            <div class="border p-3">
                                {!! $item->answer !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
