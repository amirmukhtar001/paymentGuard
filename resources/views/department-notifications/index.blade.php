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
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="table-responsive" style="min-height: 200px">
                    <table id="department-notifications-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="width: 160px;">Actions</th>
                                <th>Title</th>
                                <th>Order Number</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Attachment</th>
                                <th>Department</th>
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

        let table = $('#department-notifications-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route("settings.department-notifications.datatable") }}',
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
                    name: 'department_notifications.title'
                },
                {
                    data: 'order_number',
                    name: 'department_notifications.order_number',
                    searchable: false
                },
                {
                    data: 'notification_date',
                    name: 'department_notifications.notification_date',
                    searchable: false
                },
                {
                    data: 'status',
                    name: 'department_notifications.status',
                    searchable: false
                },
                {
                    data: 'document',
                    name: 'media.file_path',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'department_name',
                    name: 'department.name',
                    defaultContent: '-'
                },
            ],

            order: [
                [3, 'desc']
            ], // latest by date
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
                text: '<i class="bx bx-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">New Notification</span>',
                className: 'btn btn-primary',
                action: function() {
                    window.location.href = "{{ route('settings.department-notifications.create') }}";
                }
            }],

            language: { 
                emptyTable: 'No notifications found',
                zeroRecords: 'No matching notifications found'
            },

            responsive: true,
        });

        $('#filter_company_id, #filter_category_id, #filter_department_id, #filter_status').on('change', function() {
            table.ajax.reload();
        });

    });
</script>
@endpush