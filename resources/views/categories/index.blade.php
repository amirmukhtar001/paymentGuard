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
                <!-- <div class="header-elements">
                    <a href="{{ route('settings.categories.create') }}" class="btn btn-success"><i class="bx bx-plus"></i> Add Category </a>
                </div> -->
            </div>
            <div class="card-body">
                {{-- 🔍 Filters row --}}
                <div class="row mb-3">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="filter_status">Status</label>
                            <select id="filter_status" class="form-control">
                                <option value="">All</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="filter_category_type_id">Category Type</label>
                            <select id="filter_category_type_id" class="form-control">
                                <option value="">All Types</option>
                                @foreach($categoryTypes as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                {{-- /Filters row --}}
                <div class="table-responsive" style="min-height: 200px">
                    <table id="categories-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>

                                <th style="width: 160px;">Actions</th>
                                <th>Title</th>
                                <th>Slug</th>
                                <th>Parent</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Sort</th>
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
        let table = $('#categories-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route("settings.categories.datatable") }}',
                data: function(d) {
                    d.status = $('#filter_status').val();
                    d.category_type_id = $('#filter_category_type_id').val();
                },
                error: function(xhr, error, thrown) {
                    console.error('DataTable AJAX Error:', error, xhr.responseText);
                }
            },
            columns: [{
                    data: 'actions',
                    name: 'actions',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'title',
                    name: 'categories.title'
                },
                {
                    data: 'slug',
                    name: 'categories.slug'
                },
                {
                    data: 'parent_name',
                    name: 'parent.title',
                    defaultContent: '-'
                },
                {
                    data: 'type_name',
                    name: 'type.title',
                    defaultContent: '-'
                },
                {
                    data: 'status',
                    name: 'categories.status',
                    orderable: true,
                    searchable: false
                },
                {
                    data: 'sort_order',
                    name: 'categories.sort_order'
                },
            ],

            order: [
                [0, 'desc']
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
                            filename: 'category-types-' + new Date().toISOString().slice(0, 10)
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
                            filename: 'category-types-' + new Date().toISOString().slice(0, 10)
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
                            filename: 'category-types-' + new Date().toISOString().slice(0, 10),
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
                    text: '<i class="bx bx-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">New Category</span>',
                    className: 'btn btn-primary',
                    action: function(e, dt, node, config) {
                        window.location.href = "{{ route('settings.categories.create') }}";
                    }
                }
            ],
            language: {
                processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>',
                emptyTable: 'No category types found',
                zeroRecords: 'No matching category types found'
            },
            responsive: true,
            drawCallback: function() {
                // Re-initialize tooltips or other plugins if needed
            }

        });

        $('#filter_status, #filter_category_type_id').on('change', function() {
            table.ajax.reload();
        });
    });
</script>
@endpush