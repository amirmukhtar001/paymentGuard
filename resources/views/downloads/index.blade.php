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
            </div>

            <div class="card-body">

                {{-- Filters --}}
                <div class="row mb-3">
                    <div class="col-md-3">
                        @include('components.companies', [
                        'companies' => $companies,
                        'select_id' => 'filter_company_id',
                        'label' => 'Web Site',
                        'selected_company_id' => $selected_company_id
                        ])
                    </div>

                    <div class="col-md-3">
                        <label for="filter_category_id">Select Category</label>
                        @include('components.categories', [
                        'categories' => $categories,
                        'select_id' => 'filter_category_id',
                        'label' => 'Category',
                        'placeholder' => 'Select Category'
                        ])
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="filter_department_id">Department</label>
                            <select id="filter_department_id" class="form-control select2">
                                <option value="">All</option>
                                @foreach($departments as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="filter_status">Status</label>
                            <select id="filter_status" class="form-control">
                                <option value="">All</option>
                                <option value="Active">Active</option>
                                <option value="InActive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="table-responsive" style="min-height: 200px">
                    <table id="downloads-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="width: 160px;">Actions</th>
                                <th>Title</th>
                                <th>Attachment Date</th>
                                <th>Status</th>
                                <th>Attachment</th>
                                <th>Category</th>
                                <th>Department</th>
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
    $(document).ready(function() {

        let table = $('#downloads-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route("settings.downloads.datatable") }}',
                data: function(d) {
                    d.company_id = $('#filter_company_id').val();
                    d.category_id = $('#filter_category_id').val();
                    d.department_id = $('#filter_department_id').val();
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
                    data: 'title',
                    name: 'downloads.title'
                },
                {
                    data: 'attachment_date',
                    name: 'downloads.attachment_date',
                    searchable: false
                },
                {
                    data: 'status',
                    name: 'downloads.status',
                    searchable: false
                },
                {
                    data: 'document',
                    name: 'media.file_path',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'category_name',
                    name: 'category.title',
                    defaultContent: '-'
                },
                {
                    data: 'department_name',
                    name: 'department.name',
                    defaultContent: '-'
                },
                {
                    data: 'company_name',
                    name: 'company.title',
                    defaultContent: '-'
                },
            ],

            order: [
                [2, 'desc']
            ], // latest by attachment_date
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
                text: '<i class="bx bx-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">New Download</span>',
                className: 'btn btn-primary',
                action: function() {
                    window.location.href = "{{ route('settings.downloads.create') }}";
                }
            }],

            language: {
                emptyTable: 'No downloads found',
                zeroRecords: 'No matching downloads found'
            },

            responsive: true,
        });

        $('#filter_company_id, #filter_category_id, #filter_department_id, #filter_status').on('change', function() {
            table.ajax.reload();
        });

    });
</script>
@endpush