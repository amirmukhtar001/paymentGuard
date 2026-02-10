@extends('layouts.' . config('settings.active_layout'))

@push('stylesheets')
    @include('components.datatables.cdn-styles')
    <style>
        .table > :not(caption) > * > * {
            padding: 5px;
        }
    </style>
@endpush

@section('content')
<div class="row">
    <div class="col-12">

        <div class="card">
            <div class="card-header header-elements-inline">
                <h6 class="card-title pb-0 mb-0">Filter</h6>
                <br>
                <div class="row mb-4">
                    <div class="col-md-4">
                        <select id="filter_user" class="form-control" data-filter="user_id">
                            <option value="">All Users</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="date" id="filter_date_from" class="form-control" data-filter="date_from" placeholder="From Date">
                    </div>
                    <div class="col-md-4">
                        <input type="date" id="filter_date_to" class="form-control" data-filter="date_to" placeholder="To Date">
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive" style="min-height: 200px">
                    <table id="userLogsTable" class="table table-bordered myDataTable"
                        data-route="{{ route('settings.user_logs.list') }}">
                        <thead>
                            <tr>
                                <th width="15%">User</th>
                                <th width="10%">IP Address</th>
                                <th width="10%">Action</th>
                                <th width="25%">Device</th>
                                <th width="20%">Created At</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
    @include('components.datatables.cdn-scripts')
    <script>
        $(function() {
            const table = $('#userLogsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('settings.user_logs.list') }}",
                    type: "GET",
                    data: function(d) {
                        d._token = $('meta[name="csrf-token"]').attr('content');
                        d.user_id = $('#filter_user').val();
                        d.date_from = $('#filter_date_from').val();
                        d.date_to = $('#filter_date_to').val();
                    },
                    error: function(xhr, error) {
                        console.error('DataTable AJAX Error:', error, xhr.responseText);
                    }
                },
                columns: [
                    { data: 'user_name', name: 'user_name' },
                    { data: 'ip_address', name: 'ip_address' },
                    { data: 'action', name: 'action' },
                    { data: 'user_agent', name: 'user_agent' },
                    { data: 'created_at', name: 'created_at' }
                ],
                order: [[4, 'desc']],
                pageLength: 30,
                lengthMenu: [[30, 50, 100, 500, 1000], [30, 50, 100, 500, 1000]],
                dom: '<"card-header flex-column flex-md-row"<"head-label"><"dt-action-buttons text-end pt-3 pt-md-0"B>>' +
                     '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>' +
                     'rt' +
                     '<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                buttons: [
                    {
                        extend: 'collection',
                        className: 'btn btn-label-primary dropdown-toggle me-2',
                        text: '<i class="bx bx-export me-sm-1"></i> <span class="d-none d-sm-inline-block">Export</span>',
                        buttons: [
                            {
                                extend: 'print',
                                text: '<i class="bx bx-printer me-1"></i> Print',
                                className: 'dropdown-item',
                                exportOptions: {
                                    columns: ':visible'
                                },
                                customize: function(win) {
                                    $(win.document.body).find('table').addClass('table-bordered');
                                }
                            },
                            {
                                extend: 'csv',
                                text: '<i class="bx bx-file me-1"></i> CSV',
                                className: 'dropdown-item',
                                exportOptions: {
                                    columns: ':visible'
                                },
                                filename: 'user-logs-' + new Date().toISOString().slice(0, 10)
                            },
                            {
                                extend: 'excel',
                                text: '<i class="bx bxs-file-export me-1"></i> Excel',
                                className: 'dropdown-item',
                                exportOptions: {
                                    columns: ':visible'
                                },
                                filename: 'user-logs-' + new Date().toISOString().slice(0, 10)
                            },
                            {
                                extend: 'pdfHtml5',
                                text: '<i class="bx bxs-file-pdf me-1"></i> PDF',
                                className: 'dropdown-item',
                                exportOptions: {
                                    columns: ':visible'
                                },
                                filename: 'user-logs-' + new Date().toISOString().slice(0, 10),
                                orientation: 'landscape',
                                pageSize: 'A4',
                                customize: function(doc) {
                                    doc.defaultStyle.fontSize = 9;
                                    doc.styles.tableHeader.fontSize = 10;
                                    doc.styles.tableHeader.alignment = 'center';
                                }
                            },
                            {
                                extend: 'copy',
                                text: '<i class="bx bx-copy me-1"></i> Copy',
                                className: 'dropdown-item',
                                exportOptions: {
                                    columns: ':visible'
                                }
                            }
                        ]
                    }
                ],
                language: {
                    processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>',
                    emptyTable: 'No user logs found',
                    zeroRecords: 'No matching user logs found'
                },
                responsive: true
            });

            $('.head-label').html('<h5 class="card-title mb-0">User Logs</h5>');

            $('#filter_user, #filter_date_from, #filter_date_to').on('change', function() {
                table.ajax.reload();
            });
        });
    </script>
@endpush
