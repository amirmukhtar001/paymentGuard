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

                    {{-- Company Filter (required for reorder) --}}
                    <div class="col-md-3">
                        <div class="form-group">
                            @include('components.companies', [
                            'companies' => $companies,
                            'select_id' => 'filter_company_id',
                            'label' => 'Web Site',
                            'selected_company_id' => $selected_company_id
                            ])
                        </div>
                    </div>

                    {{-- Department Filter --}}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="filter_department_id">Department</label>
                            <select id="filter_department_id" class="form-control">
                                <option value="">All Departments</option>
                                @foreach($departments as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Position Type Filter 
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="filter_position_type_id">Position Type</label>
                            <select id="filter_position_type_id" class="form-control">
                                <option value="">All Position Types</option>
                                @foreach($positionTypes as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>--}}

                    {{-- Display on Home Filter --}}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="filter_display_on_home">Display on Home</label>
                            <select id="filter_display_on_home" class="form-control">
                                <option value="">All</option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                        </div>
                    </div>
                </div>


                <div class="table-responsive">
                    <table id="employees-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="width:40px;">⇅</th>
                                <th style="width:160px;">Actions</th>
                                <th>Name</th>
                                <th>Web Site</th>
                                <th>Department</th>
                                <th>Working Since</th>
                                <th>Display on Home</th>
                                <th>Has Message</th>
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

        let table = $('#employees-table').DataTable({
            processing: true,
            serverSide: true,
            rowId: 'id', // ✅ IMPORTANT for reorder
            order: [
                [0, 'asc']
            ], // visual order; actual DB order handled server-side
            ajax: {
                url: '{{ route("settings.employees.datatable") }}',
                data: function(d) {
                    d.company_id = $('#filter_company_id').val();
                    d.department_id = $('#filter_department_id').val();
                    d.position_type_id = $('#filter_position_type_id').val();
                    d.display_on_home = $('#filter_display_on_home').val();
                }
            },
            rowReorder: {
                selector: '.reorder-handle'
            },
            columnDefs: [{
                targets: 0,
                visible: true
            }],
            columns: [{
                    data: null,
                    defaultContent: '<span class="reorder-handle">⇅</span>',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'actions',
                    name: 'actions',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'name',
                    name: 'employees.name'
                },
                {
                    data: 'company',
                    name: 'company.title'
                },
                {
                    data: 'department',
                    name: 'department.name'
                },
                {
                    data: 'working_since',
                    name: 'employees.working_since'
                },
                {
                    data: 'display_on_home',
                    name: 'display_on_home'
                },
                {
                    data: 'message',
                    orderable: false,
                    searchable: false
                }
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
                text: '<i class="bx bx-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">New Employee</span>',
                className: 'btn btn-primary',
                action: function() {
                    window.location.href = "{{ route('settings.employees.create') }}";
                }
            }],
            language: {
                processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>',
                emptyTable: 'No employees found',
                zeroRecords: 'No matching employees found'
            },
        });

        // 🔁 Reload on filter change
        $('#filter_company_id, #filter_department_id, #filter_position_type_id, #filter_display_on_home')
            .on('change', function() {
                table.ajax.reload();
            });

        // ✅ Company-based reorder logic (same behavior as your leaders listing)
        table.on('row-reorder', function(e, diff, edit) {
            if (!diff.length) return;

            let companyId = $('#filter_company_id').val();
            if (!companyId) {
                alert('Please select a Web Site first to reorder within that company.');
                table.ajax.reload(null, false); // reset to last known state
                return;
            }

            requestAnimationFrame(function() {
                let items = [];
                $('#employees-table tbody tr').each(function(index) {
                    let rowData = table.row(this).data();
                    if (rowData && rowData.id) {
                        items.push({
                            id: rowData.id,
                            sort_order: index + 1
                        });
                    }
                });

                $.ajax({
                    url: '{{ route("settings.employees.reorder") }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: {
                        company_id: companyId,
                        items: items
                    },
                    success: function() {
                        table.ajax.reload(null, false);
                    },
                    error: function(xhr) {
                        console.error('Reorder error:', xhr);
                        table.ajax.reload(null, false);
                    }
                });
            });
        });

    });
</script>
@endpush