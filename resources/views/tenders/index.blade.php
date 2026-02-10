@extends('layouts.' . config('settings.active_layout'))

@push('stylesheets')
@include('components.datatables.cdn-styles')
@endpush

@section('content')
<div class="row">
    <div class="col-12">

        <div class="card">
            <div class="card-body">

                {{-- FILTERS --}}
                <div class="row mb-3">
                    {{-- Department Filter --}}
                    <div class="col-md-3">
                        <label for="filter_department_id">Department</label>
                        <select id="filter_department_id" class="form-control">
                            <option value="">All Departments</option>
                            @foreach($departments as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        @include('components.companies', ['companies' => $companies, 'select_id' => 'filter_company_id', 'label' => 'Web Site', 'selected_company_id' => $selected_company_id])
                    </div>

                    <div class="col-md-3">
                        <label for="filter_status">Status</label>
                        <select id="filter_status" class="form-control">
                            <option value="">All Statuses</option>
                            @foreach($statuses as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="filter_tender_type">Tender Type</label>
                        <select id="filter_tender_type" class="form-control">
                            <option value="">All Types</option>
                            @foreach($tenderTypes as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- DATATABLE --}}
                <div class="table-responsive">
                    <table id="tenders-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Actions</th>
                                <th>Tender No.</th>
                                <th>Title</th>
                                <th>Status</th>
                                <th>Type</th>
                                <th>Department</th>
                                <th>Web Site</th>
                                <th>Closing Date</th>
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

        let table = $('#tenders-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route("settings.tenders.datatable") }}',
                data: function(d) {
                    d.department_id = $('#filter_department_id').val(); // NEW
                    d.company_id = $('#filter_company_id').val();
                    d.status = $('#filter_status').val();
                    d.tender_type = $('#filter_tender_type').val();
                }
            },
            columns: [{
                    data: 'actions',
                    name: 'actions',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'tender_number',
                    name: 'tenders.tender_number'
                },
                {
                    data: 'title',
                    name: 'tenders.title'
                },
                {
                    data: 'status',
                    name: 'tenders.status'
                },
                {
                    data: 'tender_type',
                    name: 'tenders.tender_type'
                },
                {
                    data: 'department_name',
                    name: 'department_name'
                },
                {
                    data: 'company_title',
                    name: 'company_title'
                },
                {
                    data: 'closing_date',
                    name: 'tenders.closing_date'
                },
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
                text: '<i class="bx bx-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">New Tender</span>',
                className: 'btn btn-primary',
                action: function() {
                    window.location.href = "{{ route('settings.tenders.create') }}";
                }
            }],
            language: { 
                emptyTable: 'No tenders found',
                zeroRecords: 'No matching tenders found'
            },
        });

        $('#filter_company_id, #filter_status, #filter_tender_type').on('change', function() {
            table.ajax.reload();
        });


        // ================
        // DEPARTMENT → COMPANIES (FILTER)
        // ================
        function loadFilterCompaniesByDepartment(departmentId) {
            var $companySelect = $('#filter_company_id');

            // Clear existing options
            $companySelect.empty();
            $companySelect.append('<option value="">All Web Sites</option>');

            if (!departmentId) {
                // When no department selected, either:
                //   - keep all companies (if you want); OR
                //   - just leave "All Web Sites" as-is.
                // Here we just reload table without restricting companies list.
                table.ajax.reload();
                return;
            }

            $.ajax({
                url: "{{ route('settings.departments.companies', ['department' => 'DEPT_ID']) }}"
                    .replace('DEPT_ID', departmentId),
                type: 'GET',
                success: function(companies) {
                    companies.forEach(function(company) {
                        var option = new Option(company.title, company.id, false, false);
                        $companySelect.append(option);
                    });

                    // Trigger change so DataTable reloads
                    $companySelect.trigger('change');
                },
                error: function() {
                    console.error('Failed to load companies for department ' + departmentId);
                }
            });
        }

        $('#filter_department_id').on('change', function() {
            var departmentId = $(this).val();
            loadFilterCompaniesByDepartment(departmentId);

            // Also reload table because department filter itself affects query
            table.ajax.reload();
        });


    });
</script>
@endpush