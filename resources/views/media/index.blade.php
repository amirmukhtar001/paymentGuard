@extends('layouts.' . config('settings.active_layout'))

@push('stylesheets')
@include('components.datatables.cdn-styles')
@endpush

@section('content')
<div class="row">
    <div class="col-12">

        <div class="card">
            <div class="card-header header-elements-inline">
                <h5 class="card-title">{{ $title }}</h5>
                <div class="header-elements">
                    <a href="{{ route('settings.media.create') }}" class="btn btn-success">
                        <i class="bx bx-plus"></i> Upload Media
                    </a>
                </div>
            </div>

            <div class="card-body">

                {{-- Filters --}}
                <div class="row mb-3">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="filter_company_id">Website</label>
                            <select id="filter_company_id" class="form-control">
                                <option value="">All Websites</option>
                                @foreach($companies as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="filter_category_id">Category</label>
                            <select id="filter_category_id" class="form-control">
                                <option value="">All Categories</option>
                                @foreach($categories as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="filter_kind">Kind/Type</label>
                            <select id="filter_kind" class="form-control">
                                <option value="">All</option>
                                <option value="image">Image</option>
                                <option value="video">Video</option>
                                <option value="audio">Audio</option>
                                <option value="document">Document</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="filter_status">Status</label>
                            <select id="filter_status" class="form-control">
                                <option value="">All</option>
                                <option value="active">Active</option>
                                <option value="draft">Draft</option>
                                <option value="archived">Archived</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="table-responsive" style="min-height: 200px">
                    <table id="media-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="width: 160px;">Actions</th>
                                <th>Preview</th>
                                <th>Title</th>
                                <th>Kind</th>
                                <th>Status</th>
                                <th>Website</th>
                                <th>Category</th>
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

<script type="text/javascript">
    $(document).ready(function() {

        let table = $('#media-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route("settings.media.datatable") }}',
                data: function(d) {
                    d.company_id = $('#filter_company_id').val();
                    d.category_id = $('#filter_category_id').val();
                    d.kind = $('#filter_kind').val();
                    d.status = $('#filter_status').val();
                }
            },
            columns: [{
                    data: 'actions',
                    name: 'actions',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'preview',
                    name: 'preview',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'title',
                    name: 'media.title',
                    defaultContent: '-'
                },
                {
                    data: 'kind',
                    name: 'media.kind'
                },
                {
                    data: 'status',
                    name: 'media.status',
                    searchable: false
                },
                {
                    data: 'company_name',
                    name: 'company.title',
                    defaultContent: '-'
                },
                {
                    data: 'category_name',
                    name: 'category_name',
                    defaultContent: '-'
                }
            ],
            order: [
                [2, 'desc']
            ],
            pageLength: 20,
            lengthMenu: [
                [20, 50, 100, 500, 1000],
                [20, 50, 100, 500, 1000]
            ],
            dom: '<"card-header flex-column flex-md-row"<"head-label"><"dt-action-buttons text-end pt-3 pt-md-0"B>>' +
                '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>' +
                'rt' +
                '<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            buttons: [{
                    extend: 'collection',
                    className: 'btn btn-label-primary dropdown-toggle me-2',
                    text: '<i class="bx bx-export me-sm-1"></i> <span class="d-none d-sm-inline-block">Export</span>',
                    buttons: [{
                            extend: 'print',
                            text: '<i class="bx bx-printer me-1"></i> Print',
                            className: 'dropdown-item',
                            exportOptions: {
                                columns: function(idx, data, node) {
                                    return $(node).text().trim().toLowerCase() !== 'actions';
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
                                    return $(node).text().trim().toLowerCase() !== 'actions';
                                }
                            },
                            filename: 'media-' + new Date().toISOString().slice(0, 10)
                        },
                        {
                            extend: 'excel',
                            text: '<i class="bx bxs-file-export me-1"></i> Excel',
                            className: 'dropdown-item',
                            exportOptions: {
                                columns: function(idx, data, node) {
                                    return $(node).text().trim().toLowerCase() !== 'actions';
                                }
                            },
                            filename: 'media-' + new Date().toISOString().slice(0, 10)
                        },
                        {
                            extend: 'pdfHtml5',
                            text: '<i class="bx bxs-file-pdf me-1"></i> PDF',
                            className: 'dropdown-item',
                            exportOptions: {
                                columns: function(idx, data, node) {
                                    return $(node).text().trim().toLowerCase() !== 'actions';
                                }
                            },
                            filename: 'media-' + new Date().toISOString().slice(0, 10),
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
                                    return $(node).text().trim().toLowerCase() !== 'actions';
                                }
                            }
                        }
                    ]
                },
                {
                    text: '<i class="bx bx-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">Upload Media</span>',
                    className: 'btn btn-primary',
                    action: function() {
                        window.location.href = "{{ route('settings.media.create') }}";
                    }
                }
            ],
            language: {
                processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>',
                emptyTable: 'No media found',
                zeroRecords: 'No matching media found'
            },
            responsive: true,
        });

        $('#filter_company_id, #filter_category_id, #filter_kind, #filter_status').on('change', function() {
            table.ajax.reload();
        });
    });
</script>
@endpush
