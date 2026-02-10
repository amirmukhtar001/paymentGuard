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
                {{-- 🔍 Filters row --}}
                <div class="row mb-3">
                    {{-- Status filter --}}
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
                            <label for="filter_department_type">Department Type</label>
                            <select id="filter_department_type" class="form-control">
                                <option value="">All Types</option>
                                @foreach($departmentTypes as $type)
                                <option value="{{ $type->value }}">{{ $type->value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                    {{-- Has Website filter
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="filter_has_website">Has Website</label>
                                <select id="filter_has_website" class="form-control">
                                    <option value="">All</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </div>
                        </div>--}}
                </div>
                {{-- /Filters row --}}

                <div class="table-responsive" style="min-height: 200px">
                    <table id="departments-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="width: 160px;">Actions</th>
                                <th>Department Name</th>
                                <th>Department Type</th>
                                <th>Parent Department</th>
                                <th>Department Logo</th>
                                <th>Department Picture</th>
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
<script type="text/javascript">
    $(document).ready(function() {
        let table = $('#departments-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route("settings.departments.datatable") }}',
                data: function(d) {
                    d.status = $('#filter_status').val();
                    d.department_type = $('#filter_department_type').val(); // ✅ NEW
                    d.has_website = $('#filter_has_website').length ? $('#filter_has_website').val() : '';
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
                    data: 'name',
                    name: 'departments.name'
                },
                {
                    data: 'department_type',
                    name: 'departments.department_type',
                    defaultContent: '-'
                },
                {
                    data: 'parent_name',
                    name: 'parent.name',
                    defaultContent: '-'
                },

                // ✅ NEW
                {
                    data: 'media',
                    name: 'media.title',
                    orderable: false,
                    searchable: false,
                    defaultContent: '-'
                },
                {
                    data: 'cover_media',
                    name: 'coverMedia.title',
                    orderable: false,
                    searchable: false,
                    defaultContent: '-'
                },

                {
                    data: 'status',
                    name: 'departments.status',
                    orderable: true,
                    searchable: false
                }
            ],

            order: [
                [1, 'asc']
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
            buttons: [

                {
                    text: '<i class="bx bx-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">New Department</span>',
                    className: 'btn btn-primary',
                    action: function(e, dt, node, config) {
                        window.location.href = "{{ route('settings.departments.create') }}";
                    }
                }
            ],
            language: {
            //  processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>',
                emptyTable: 'No departments found',
                zeroRecords: 'No matching departments found'
            },
            responsive: true,
            drawCallback: function() {
                // Re-initialize tooltips or other plugins if needed
            }
        });

        $('#filter_status, #filter_department_type').on('change', function() {
            table.ajax.reload();
        });

        $(document).on('change', '#filter_has_website', function() {
            table.ajax.reload();
        });
    });
</script>
@endpush