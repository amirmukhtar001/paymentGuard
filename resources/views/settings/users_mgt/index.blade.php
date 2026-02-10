@extends('layouts.' . config('settings.active_layout'))

@push('stylesheets')
    @include('components.datatables.cdn-styles')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <!-- Filter -->
            <div class="card">
                <div class="card-header header-elements-inline">
                    <h6 class="card-title pb-0 mb-0">Filter</h6>
                    <br>
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <label for="filter_role" class="form-label">Role</label>
                            <select id="filter_role" class="form-control" data-filter="role_id">
                                <option value="">All Roles</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="filter_org" class="form-label">Organization</label>
                            <select id="filter_org" class="form-control" data-filter="org_id">
                                <option value="">All Organizations</option>
                                @foreach ($companies as $org)
                                    <option value="{{ $org->id }}">{{ $org->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="filter_unit" class="form-label">Unit</label>
                            <select id="filter_unit" class="form-control" data-filter="unit_id">
                                <option value="">All Units</option>
                                @foreach ($sections as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        {{-- <div class="col-md-3">
                            <label for="filter_district" class="form-label">District</label>
                            {!! Form::select('district_id', [], null, [
                                'class' => 'dynamic-select',
                                'data-filter'=>'district_id',
                                'id' => 'district_id',
                                'multiple' => 'multiple',
                                'data-route' => route('dynamic.dropDown'),
                                'data-statment' => \Illuminate\Support\Facades\Crypt::encrypt([
                                    'model' => 'districts',
                                    'connection' => 'mysql',
                                    'label' => 'title',
                                    'value' => 'id',
                                ]),
                            ]) !!}
                        </div> --}}
                    </div>
                    <div class="row mb-4">
                   {{--      <div class="col-md-3">
                            <label for="filter_tehsil" class="form-label">Tehsil</label>
                            {!! Form::select('tehsil_id', [], null, [
                                'class' => 'dynamic-select',
                                'multiple' => 'multiple',
                                'data-filter' => 'tehsil_id',
                                'id' => 'tehsil_ids',
                                'data-route' => route('dynamic.dropDown'),
                                'data-statment' => \Illuminate\Support\Facades\Crypt::encrypt([
                                    'model' => 'tehsils',
                                    'connection' => 'mysql',
                                    'label' => 'title',
                                    'value' => 'id',
                                ]),
                                'data-conditions' => json_encode(['column' => 'district_id', 'operator' => '=', 'value' => 'district_id']),
                            ]) !!}
                        </div> --}}
                        {{-- <div class="col-md-3">
                            <label for="filter_unioncouncil" class="form-label">Union Council</label>
                            {!! Form::select('union_council_id', [], null, [
                                'class' => 'dynamic-select',
                                'data-filter' => 'union_council_id',
                                'multiple' => 'multiple',
                                'data-route' => route('dynamic.dropDown'),
                                'data-statment' => \Illuminate\Support\Facades\Crypt::encrypt([
                                    'model' => 'union_councillors',
                                    'connection' => 'mysql',
                                    'label' => 'name',
                                    'value' => 'id',
                                    'concat_column' => 'ur_name',
                                ]),
                                'data-conditions' => json_encode(['column' => 'tehsil_id', 'operator' => '=', 'value' => 'tehsil_id']),
                            ]) !!}
                        </div> --}}
                        {{-- <div class="col-md-3">
                            <label for="filter_village" class="form-label">Village</label>
                            {!! Form::select('village_id', [], null, [
                                'class' => 'dynamic-select',
                                'data-filter' => 'village_id',
                                'multiple' => 'multiple',
                                'data-route' => route('dynamic.dropDown'),
                                'data-statment' => \Illuminate\Support\Facades\Crypt::encrypt([
                                    'model' => 'uc_vc_lists',
                                    'connection' => 'mysql',
                                    'label' => 'name',
                                    'value' => 'id',
                                ]),
                                'data-conditions' => json_encode([
                                    'column' => 'union_council_id',
                                    'operator' => '=',
                                    'value' => 'union_council_id',
                                ]),
                            ]) !!}
                        </div> --}}
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <div>
                                <button id="loadDataButton" class="btn btn-primary">
                                    <i class="bx bx-filter-alt me-1"></i> Filter
                                </button>

                                <button id="resetFiltersButton" class="btn btn-secondary">
                                    <i class="bx bx-refresh me-1"></i> Reset Filters
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive" style="min-height: 200px">
                        <table id="users-list" class="table table-bordered myDataTable"
                            data-route="{{ route('settings.users-mgt.users-list-dt') }}" raw-columns="actions,roles,status,verified_by_name,verified_at_formatted,districts_list,tehsils_list,unioncouncils_list,villages_list"
                            data-filters="filter_role,filter_org,filter_unit,filter_district,filter_tehsil,filter_unioncouncil,filter_village">
                            <thead>
                                <tr>
                                    <th style="width: 10%">Action</th>
                                    <th style="width: 10%">Name</th>
                                    <th style="width: 10%">Email</th>
                                    <th style="width: 5%">Username</th>
                                    {{-- <th style="width: 5%">Parent User</th> --}}
                                    {{-- <th style="width: 5%">No. of Children</th> --}}
                                    <th style="width: 5%">{{ config('settings.company_title') }}</th>
                                    <th style="width: 5%">{{ config('settings.section_title') }}</th>
                                    <th style="width: 5%">Roles</th>
                                    <th style="width: 5%">Status</th>
                                    <th style="width: 8%">Verified By</th>
                                    {{-- <th style="width: 8%">Verified At</th> --}}
                                    {{-- <th style="width: 8%">Districts</th>
                                    <th style="width: 8%">Tehsils</th>
                                    <th style="width: 8%">Union Councils</th>
                                    <th style="width: 8%">Villages</th> --}}
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
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/js/Settings/drop-down.js') }}"></script>
    <script>
        $(function() {
            const actionColumnFilter = function(idx, data, node) {
                return $(node).text().trim().toLowerCase() !== 'action';
            };

            function collectFilters() {
                const filters = {};
                $('[data-filter]').each(function() {
                    const key = $(this).data('filter');
                    const value = $(this).val();
                    filters[key] = value;
                });
                return filters;
            }

            const table = $('#users-list').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('settings.users-mgt.users-list-dt') }}",
                    type: "GET",
                    data: function(d) {
                        d._token = $('meta[name="csrf-token"]').attr('content');
                        return $.extend({}, d, collectFilters());
                    },
                    error: function(xhr, error) {
                        console.error('DataTable AJAX Error:', error, xhr.responseText);
                    }
                },
                columns: [
                    { data: 'actions', name: 'id', orderable: false, searchable: false },
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    { data: 'username', name: 'username' },
                    { data: 'company_title', name: 'company.title' },
                    { data: 'section_title', name: 'section.title' },
                    { data: 'roles', name: 'roles.name' },
                    { data: 'status', name: 'deleted_at' },
                    { data: 'verified_by_name', name: 'verifiedBy.name' }
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
                                filename: 'users-' + new Date().toISOString().slice(0, 10)
                            },
                            {
                                extend: 'excel',
                                text: '<i class="bx bxs-file-export me-1"></i> Excel',
                                className: 'dropdown-item',
                                exportOptions: {
                                    columns: actionColumnFilter
                                },
                                filename: 'users-' + new Date().toISOString().slice(0, 10)
                            },
                            {
                                extend: 'pdfHtml5',
                                text: '<i class="bx bxs-file-pdf me-1"></i> PDF',
                                className: 'dropdown-item',
                                exportOptions: {
                                    columns: actionColumnFilter
                                },
                                filename: 'users-' + new Date().toISOString().slice(0, 10),
                                orientation: 'landscape',
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
                    emptyTable: 'No users found',
                    zeroRecords: 'No matching users found'
                },
                responsive: true
            });

            $('.head-label').html('<h5 class="card-title mb-0">Users List</h5>');

            $('#loadDataButton').on('click', function(e) {
                e.preventDefault();
                table.ajax.reload();
            });

            $('#resetFiltersButton').on('click', function(e) {
                e.preventDefault();
                $('[data-filter]').each(function() {
                    const isMultiple = $(this).prop('multiple');
                    $(this).val(isMultiple ? [] : '').trigger('change');
                });
                table.ajax.reload();
            });

            // Cascading dropdowns functionality
            $('#filter_district').change(function() {
                var districtId = $(this).val();
                var tehsilSelect = $('#filter_tehsil');
                var unioncouncilSelect = $('#filter_unioncouncil');
                var villageSelect = $('#filter_village');

                tehsilSelect.html('<option value="">All Tehsils</option>');
                unioncouncilSelect.html('<option value="">All Union Councils</option>');
                villageSelect.html('<option value="">All Villages</option>');

                if (districtId) {
                    $.get('{{ url('api/v1/settings/districts') }}/' + districtId + '/tehsils/lite', function(data) {
                        if (data.success && data.data) {
                            $.each(data.data, function(key, value) {
                                tehsilSelect.append('<option value="' + value.id + '">' + value.title + '</option>');
                            });
                        }
                    });
                }
            });

            $('#filter_tehsil').change(function() {
                var tehsilId = $(this).val();
                var unioncouncilSelect = $('#filter_unioncouncil');
                var villageSelect = $('#filter_village');

                unioncouncilSelect.html('<option value="">All Union Councils</option>');
                villageSelect.html('<option value="">All Villages</option>');

                if (tehsilId) {
                    $.get('{{ url('api/v1/settings/tehsils') }}/' + tehsilId + '/union_councils/lite', function(data) {
                        if (data.success && data.data) {
                            $.each(data.data, function(key, value) {
                                unioncouncilSelect.append('<option value="' + value.id + '">' + value.title + '</option>');
                            });
                        }
                    });
                }
            });

            $('#filter_unioncouncil').change(function() {
                var unioncouncilId = $(this).val();
                var villageSelect = $('#filter_village');

                villageSelect.html('<option value="">All Villages</option>');

                if (unioncouncilId) {
                    // Placeholder for future enhancement
                }
            });

        });
    </script>
@endpush
