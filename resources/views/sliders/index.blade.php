@extends('layouts.' . config('settings.active_layout'))

@push('stylesheets')
    @include('components.datatables.cdn-styles')
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    {{-- Filters --}}
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="form-group">
                                @include('components.companies', ['companies' => $companies, 'select_id' => 'filter_company_id', 'label' => 'Web Site', 'selected_company_id' => $selected_company_id])
                                {{--
                                <label for="filter_company_id">Web Sites</label>
                                <select id="filter_company_id" class="form-control">
                                    <option value="">All Web Sites</option>
                                    @foreach($companies as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </select>--}}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="filter_status">Status</label>
                                <select id="filter_status" class="form-control">
                                    <option value="">All</option>
                                    <option value="draft">Draft</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="archived">Archived</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive" style="min-height: 200px">
                        <table id="sliders-table" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 160px;">Actions</th>
                                    <th>Name</th>
                                    <th>Slug</th>
                                    <th>Status</th>
                                    <th>Sort Order</th>
                                    <th>Transition</th>
                                    <th>Autoplay (ms)</th>
                                    <th>Web Site</th>
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
        $(document).ready(function () {

            let table = $('#sliders-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route("settings.sliders.datatable") }}',
                    data: function (d) {
                        d.company_id = $('#filter_company_id').val();
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
                    data: 'name',
                    name: 'sliders.name'
                },
                {
                    data: 'slug',
                    name: 'sliders.slug'
                },
                {
                    data: 'status',
                    name: 'sliders.status',
                    searchable: false
                },
                {
                    data: 'sort_order',
                    name: 'sliders.sort_order'
                },
                {
                    data: 'transition',
                    name: 'sliders.transition'
                },
                {
                    data: 'autoplay_ms',
                    name: 'sliders.autoplay_ms'
                },
                {
                    data: 'company_name',
                    name: 'company.title',
                    defaultContent: '-'
                }
                ],
                order: [
                    [4, 'asc'], // sort_order
                    [1, 'asc'], // name
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
                            columns: function (idx, data, node) {
                                return $(node).text().trim().toLowerCase() !== 'actions';
                            }
                        },
                        customize: function (win) {
                            $(win.document.body).find('table').addClass('table-bordered');
                        }
                    },
                    {
                        extend: 'csv',
                        text: '<i class="bx bx-file me-1"></i> CSV',
                        className: 'dropdown-item',
                        exportOptions: {
                            columns: function (idx, data, node) {
                                return $(node).text().trim().toLowerCase() !== 'actions';
                            }
                        },
                        filename: 'sliders-' + new Date().toISOString().slice(0, 10)
                    },
                    {
                        extend: 'excel',
                        text: '<i class="bx bxs-file-export me-1"></i> Excel',
                        className: 'dropdown-item',
                        exportOptions: {
                            columns: function (idx, data, node) {
                                return $(node).text().trim().toLowerCase() !== 'actions';
                            }
                        },
                        filename: 'sliders-' + new Date().toISOString().slice(0, 10)
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="bx bxs-file-pdf me-1"></i> PDF',
                        className: 'dropdown-item',
                        exportOptions: {
                            columns: function (idx, data, node) {
                                return $(node).text().trim().toLowerCase() !== 'actions';
                            }
                        },
                        filename: 'sliders-' + new Date().toISOString().slice(0, 10),
                        orientation: 'portrait',
                        pageSize: 'A4',
                        customize: function (doc) {
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
                            columns: function (idx, data, node) {
                                return $(node).text().trim().toLowerCase() !== 'actions';
                            }
                        }
                    }
                    ]
                },
                {
                    text: '<i class="bx bx-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">New Slider</span>',
                    className: 'btn btn-primary',
                    action: function () {
                        window.location.href = "{{ route('settings.sliders.create') }}";
                    }
                }
                ],
                language: {
                    processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>',
                    emptyTable: 'No sliders found',
                    zeroRecords: 'No matching sliders found'
                },
                responsive: true,
            });

            $('#filter_company_id, #filter_status').on('change', function () {
                table.ajax.reload();
            });
        });
    </script>
@endpush