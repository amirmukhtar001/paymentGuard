@extends('layouts.' . config('settings.active_layout'))

@push('stylesheets')
    @include('components.datatables.cdn-styles')
    <style>
        .table> :not(caption)>*>* {
            padding: 5px;
        }
    </style>
@endpush

@section('content')
    <div class="layout-top-spacing mb-2 m-2">
        <div class="col-md-12">
            <div class="row">
                <div class="container p-0">
                    <div class="row date-table-container">
                        <div class="col-xl-12 card">
                            <div class="card-body">
                                <div class="table-responsive mb-4">
                                    <table id="myDataTable" class="table table-bordered myDataTable"
                                        data-route="{{ route('settings.menus.list') }}"
                                        raw-columns="title,parent,action">
                                        <thead>
                                            <tr>
                                                <th>Action</th>
                                                <th>#</th>
                                                <th>Title</th>
                                                <th>Parent</th>
                                                <th>Sort Order</th>
                                                {{-- <th>App</th> --}}
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
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
            const table = $('#myDataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('settings.menus.list') }}",
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
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'title', name: 'title' },
                    { data: 'parent', name: 'parent', orderable: false, searchable: false },
                    {data:'order',name:'order'}
                ],
                order: [[4, 'asc']],
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
                                    columns: function(idx, data, node) {
                                        return $(node).text().trim().toLowerCase() !== 'action';
                                    }
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
                                    columns: function(idx, data, node) {
                                        return $(node).text().trim().toLowerCase() !== 'action';
                                    }
                                },
                                filename: 'menus-' + new Date().toISOString().slice(0, 10)
                            },
                            {
                                extend: 'excel',
                                text: '<i class="bx bxs-file-export me-1"></i> Excel',
                                className: 'dropdown-item',
                                exportOptions: {
                                    columns: function(idx, data, node) {
                                        return $(node).text().trim().toLowerCase() !== 'action';
                                    }
                                },
                                filename: 'menus-' + new Date().toISOString().slice(0, 10)
                            },
                            {
                                extend: 'pdfHtml5',
                                text: '<i class="bx bxs-file-pdf me-1"></i> PDF',
                                className: 'dropdown-item',
                                exportOptions: {
                                    columns: function(idx, data, node) {
                                        return $(node).text().trim().toLowerCase() !== 'action';
                                    }
                                },
                                filename: 'menus-' + new Date().toISOString().slice(0, 10),
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
                                    columns: function(idx, data, node) {
                                        return $(node).text().trim().toLowerCase() !== 'action';
                                    }
                                }
                            }
                        ]
                    },
                    {
                        text: '<i class="bx bx-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">New Menu</span>',
                        className: 'btn btn-primary',
                        action: function() {
                            window.location.href = "{{ route('settings.menus.create') }}";
                        }
                    }
                ],
                language: {
                    processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>',
                    emptyTable: 'No menus found',
                    zeroRecords: 'No matching menus found'
                },
                responsive: true
            });

            $('.head-label').html('<h5 class="card-title mb-0">Menus List</h5>');
        });
    </script>
@endpush
