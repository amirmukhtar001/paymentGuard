@extends('layouts.' . config('settings.active_layout'))

@push('stylesheets')
    @include('components.datatables.cdn-styles')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
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
                            <select name="user_ids[]" id="user_ids" class="dynamic-select form-control" data-filter="user_ids" multiple data-route="{{ route('dynamic.dropDown') }}" data-statment="{{ \Illuminate\Support\Facades\Crypt::encrypt(['model' => 'users','connection' => 'mysql','label' => 'name','value' => 'id']) }}">
                                @foreach($users as $val => $label)
                                    <option value="{{ $val }}" @if(in_array($val, (array) $selected_users)) selected @endif>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input type="date" id="filter_date_from" data-filter="start_date" class="form-control"
                                placeholder="From Date">
                        </div>
                        <div class="col-md-4">
                            <input type="date" id="filter_date_to" data-filter="end_date" class="form-control"
                                placeholder="To Date">
                        </div>
                        <div class="col-md-4 pt-2">
                            <button id="loadDataButton" class="btn btn-primary">
                                <i class="bx bx-filter-alt me-1"></i> Filter
                            </button>

                            <button id="resetFiltersButton" class="btn btn-secondary">
                                <i class="bx bx-refresh me-1"></i> Reset Filters
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive" style="min-height: 200px">
                        <table id="myDataTable" class="table table-bordered myDataTable"
                            data-route="{{ route('settings.user_logs.all', request()->query()) }}"
                            raw-columns="properties">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>IP Address</th>
                                    <th>Action</th>
                                    <th>Device</th>
                                    <th>Created At</th>
                                    <th>Properties</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="jsonModal" tabindex="-1" aria-labelledby="jsonModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Activity Properties</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <pre id="jsonModalBody" style="white-space: pre-wrap; font-size: 0.9rem;"></pre>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @include('components.datatables.cdn-scripts')
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/js/Settings/drop-down.js') }}"></script>
    <script>
        $(function() {
            function collectFilters() {
                var filters = {};
                $('[data-filter]').each(function() {
                    var key = $(this).data('filter');
                    var value = $(this).val();
                    filters[key] = value;
                });
                return filters;
            }

            const table = $('#myDataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('settings.user_logs.all', request()->query()) }}",
                    type: "GET",
                    data: function(d) {
                        d._token = $('meta[name="csrf-token"]').attr('content');
                        var filters = collectFilters();
                        return $.extend({}, d, filters);
                    },
                    error: function(xhr, error) {
                        console.error('DataTable AJAX Error:', error, xhr.responseText);
                    }
                },
                columns: [
                    { data: 'user_name', name: 'causer_id' },
                    { data: 'ip_address', name: 'properties->ip' },
                    { data: 'action', name: 'description' },
                    { data: 'user_agent', name: 'properties->user_agent' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'properties', name: 'properties' }
                ],
                order: [[4, 'desc']],
                pageLength: 50,
                lengthMenu: [[50, 100, 500, 1000], [50, 100, 500, 1000]],
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
                                filename: 'user-activity-logs-' + new Date().toISOString().slice(0, 10)
                            },
                            {
                                extend: 'excel',
                                text: '<i class="bx bxs-file-export me-1"></i> Excel',
                                className: 'dropdown-item',
                                exportOptions: {
                                    columns: ':visible'
                                },
                                filename: 'user-activity-logs-' + new Date().toISOString().slice(0, 10)
                            },
                            {
                                extend: 'pdfHtml5',
                                text: '<i class="bx bxs-file-pdf me-1"></i> PDF',
                                className: 'dropdown-item',
                                exportOptions: {
                                    columns: ':visible'
                                },
                                filename: 'user-activity-logs-' + new Date().toISOString().slice(0, 10),
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
                    emptyTable: 'No activity logs found',
                    zeroRecords: 'No matching activity logs found'
                },
                responsive: true
            });

            $('.head-label').html('<h5 class="card-title mb-0">User Activity Logs</h5>');

            $('#loadDataButton').on('click', function(e) {
                e.preventDefault();
                table.ajax.reload();
            });

            $('#resetFiltersButton').on('click', function(e) {
                e.preventDefault();
                $('[data-filter]').each(function() {
                    const isMultiple = $(this).prop('multiple');
                    $(this).val(isMultiple ? [] : '').trigger('change');
                });
                table.ajax.reload();
            });

            $(document).on('click', '.view-properties', function() {
                const json = $(this).data('json');
                const pretty = JSON.stringify(json, null, 2);

                $('#jsonModalBody').text(pretty);
                const modal = new bootstrap.Modal(document.getElementById('jsonModal'));
                modal.show();
            });
        });
    </script>
@endpush
