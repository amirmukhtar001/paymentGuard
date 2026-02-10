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
                            data-route="{{ route('settings.diagnoses.index') }}" raw-columns="name,section,level,icd_code,description,status,action">
                            <thead>
                                <tr>
                                    <th>Action</th>
                                    <th>Name</th>
                                    <th>Section</th>
                                    <th>Level</th>
                                    <th>ICD Code</th>
                                    <th>Description</th>
                                    <th>Status</th>
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

            const tableTitle = @json($title ?? 'Diagnoses List');

            $('#myDataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('settings.diagnoses.index') }}",
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
                    { data: 'name', name: 'name' },
                    { data: 'section', name: 'section.title' },
                    { data: 'level', name: 'configuration.value' },
                    { data: 'icd_code', name: 'icd_code' },
                    { data: 'description', name: 'description' },
                    { data: 'status', name: 'is_active' }
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
                                filename: 'diagnoses-' + new Date().toISOString().slice(0, 10)
                            },
                            {
                                extend: 'excel',
                                text: '<i class="bx bxs-file-export me-1"></i> Excel',
                                className: 'dropdown-item',
                                exportOptions: {
                                    columns: actionColumnFilter
                                },
                                filename: 'diagnoses-' + new Date().toISOString().slice(0, 10)
                            },
                            {
                                extend: 'pdfHtml5',
                                text: '<i class="bx bxs-file-pdf me-1"></i> PDF',
                                className: 'dropdown-item',
                                exportOptions: {
                                    columns: actionColumnFilter
                                },
                                filename: 'diagnoses-' + new Date().toISOString().slice(0, 10),
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
                    }
                ],
                language: {
                    processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>',
                    emptyTable: 'No diagnoses found',
                    zeroRecords: 'No matching diagnoses found'
                },
                responsive: true
            });

            $('.head-label').html('<h5 class="card-title mb-0">' + tableTitle + '</h5>');
        });
    </script>
@endpush
