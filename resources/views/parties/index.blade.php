@extends('layouts.' . config('settings.active_layout'))

@push('stylesheets')
    @include('components.datatables.cdn-styles')
@endpush

@section('content')
    <div class="layout-top-spacing mb-2 m-2">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="card-title mb-0">{{ $title }}</h5>
                    <div>
                        <button type="button" class="btn btn-primary" onclick="window.location='{{ route('settings.parties.create') }}'">
                            <i class="bx bx-plus"></i> New Party
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="filter_status" class="form-label">Status</label>
                            <select id="filter_status" class="form-control">
                                <option value="">All Statuses</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="draft">Draft</option>
                            </select>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="parties-table" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Actions</th>
                                    <th>Image</th>
                                    <th>Short Name</th>
                                    <th>Full Name</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
@endsection

@push('scripts')
    @include('components.datatables.cdn-scripts')
    <script>
        $(document).ready(function() {
            let table = $('#parties-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('settings.parties.datatable') }}',
                    data: function(d) {
                        d.status = $('#filter_status').val();
                    }
                },
                columns: [{
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'preview',
                        name: 'preview',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'short_name',
                        name: 'short_name'
                    },
                    {
                        data: 'full_name',
                        name: 'full_name'
                    },
                    {
                        data: 'status_badge',
                        name: 'status',
                        orderable: true,
                        searchable: false,
                        className: 'text-center'
                    }
                ],
                order: [
                    [2, 'asc']
                ],
                pageLength: 20
            });

            $('#filter_status').on('change', function() {
                table.ajax.reload();
            });
        });
    </script>
@endpush
