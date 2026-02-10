@extends('layouts.' . config('settings.active_layout'))

@push('stylesheets')
@include('components.datatables.cdn-styles')
@endpush

@section('content')
<div class="row">
    <div class="col-12">

        <div class="card">
            <div class="card-body">

                <div class="row mb-3">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="filter_party_id">Party</label>
                            <select id="filter_party_id" class="form-control">
                                <option value="">All Parties</option>
                                @foreach($parties as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

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

                    <!-- <div class="col-md-3">
                        <div class="form-group">
                            <label for="filter_department_id">Department</label>
                            <select id="filter_department_id" class="form-control">
                                <option value="">All Departments</option>
                                @foreach($departments as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div> -->


                    {{-- NEW: Status filter --}}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="filter_status">Status</label>
                            <select id="filter_status" class="form-control">
                                <option value="">All Statuses</option>
                                @foreach(\App\Enums\StatusEnum::cases() as $case)
                                <option value="{{ $case->value }}">{{ $case->value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- NEW: Member Type filter --}}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="filter_member_type">Member Type</label>
                            <select id="filter_member_type" class="form-control">
                                <option value="">All Member Types</option>
                                @foreach(\App\Enums\MemberTypesEnum::cases() as $case)
                                <option value="{{ $case->value }}">{{ $case->value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="cabinet-members-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Actions</th>
                                <th>Name</th>
                                <th>Position</th>
                                <th>Status</th>
                                <th>Member Type</th>
                                <th>Show On Home Page</th>
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
        let table = $('#cabinet-members-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route("settings.cabinetmembers.datatable") }}',
                data: function(d) {
                    d.party_id = $('#filter_party_id').val();
                    d.department_id = $('#filter_department_id').val();
                    d.status = $('#filter_status').val(); // NEW
                    d.member_type = $('#filter_member_type').val(); // NEW 
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
                    name: 'cabinet_members.name'
                },
                {
                    data: 'position_type',
                    orderable: false,
                    searchable: false
                }, {
                    data: 'status',
                    name: 'cabinet_members.status'
                },
                {
                    data: 'member_type',
                    name: 'cabinet_members.member_type'
                },
                {
                    data: 'make_leader',
                    orderable: false,
                    searchable: false
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
                text: '<i class="bx bx-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">Create New</span>',
                className: 'btn btn-primary',
                action: function() {
                    window.location.href = "{{ route('settings.cabinetmembers.create') }}";
                }
            }],
            language: {
                processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>',
                emptyTable: 'No cabinet members found',
                zeroRecords: 'No matching cabinet members found'
            },
        });

        $('#filter_party_id, #filter_department_id, #filter_status, #filter_member_type').on('change', function() {
            table.ajax.reload();
        });

    });
</script>
@endpush