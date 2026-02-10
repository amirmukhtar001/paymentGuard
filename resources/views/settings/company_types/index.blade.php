@extends('layouts.' . config('settings.active_layout'))

@push('stylesheets')
    @include('components.datatables.cdn-styles')
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive" style="min-height: 200px">
                        <table id="myDataTable" class="table table-bordered myDataTable"
                            data-route="{{ route('settings.company-types.list') }}" raw-columns="title,action">
                            <thead>
                                <tr>
                                    <th>Action</th>
                                    <th>Title</th>
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
            const actionColumnFilter = function(idx, data, node) {
                return $(node).text().trim().toLowerCase() !== 'action';
            };

            $('#myDataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('settings.company-types.list') }}",
                    type: "GET",
                    data: function(d) {
                        d._token = $('meta[name="csrf-token"]').attr('content');
                    },
                    error: function(xhr, error) {
                        console.error('DataTable AJAX Error:', error, xhr.responseText);
                    }
                },
                columns: [
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                    { data: 'title', name: 'title' }
                ],
                order: [[1, 'asc']],
                pageLength: 20,
                lengthMenu: [[20, 50, 100, 500, 1000], [20, 50, 100, 500, 1000]],
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
                                    columns: actionColumnFilter
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
                                    columns: actionColumnFilter
                                },
                                filename: 'company-types-' + new Date().toISOString().slice(0, 10)
                            },
                            {
                                extend: 'excel',
                                text: '<i class="bx bxs-file-export me-1"></i> Excel',
                                className: 'dropdown-item',
                                exportOptions: {
                                    columns: actionColumnFilter
                                },
                                filename: 'company-types-' + new Date().toISOString().slice(0, 10)
                            },
                            {
                                extend: 'pdfHtml5',
                                text: '<i class="bx bxs-file-pdf me-1"></i> PDF',
                                className: 'dropdown-item',
                                exportOptions: {
                                    columns: actionColumnFilter
                                },
                                filename: 'company-types-' + new Date().toISOString().slice(0, 10),
                                orientation: 'portrait',
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
                                    columns: actionColumnFilter
                                }
                            }
                        ]
                    },
                    {
                        text: '<i class="bx bx-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">New {{ config('settings.company_title') }} Type</span>',
                        className: 'btn btn-primary',
                        action: function() {
                            window.location.href = "{{ route('settings.company-types.create') }}";
                        }
                    }
                ],
                language: {
                    processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>',
                    emptyTable: 'No types found',
                    zeroRecords: 'No matching types found'
                },
                responsive: true
            });

            $('.head-label').html('<h5 class="card-title mb-0">{{ config('settings.company_title') }} Types List</h5>');
        });
    </script>
@endpush
