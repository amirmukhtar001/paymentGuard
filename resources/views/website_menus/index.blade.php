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
                    <a href="{{ route('website-menus.create') }}" class="btn btn-success">
                        <i class="bx bx-plus"></i> Add Menu
                    </a>
                </div>
            </div>

            <div class="card-body">
                {{-- Filters --}}
                <div class="row mb-3">
                    <div class="col-md-3">
                        <div class="form-group">
                            @include('components.companies', ['companies' => $companies, 'select_id' => 'filter_company_id', 'label' => 'Web Site', 'selected_company_id' => $selected_company_id])
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
                    <table id="menus-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="width: 160px;">Actions</th>
                                <th>Title</th>
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
        let table = $('#menus-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route("website-menus.datatable") }}',
                data: function(d) {
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
                    data: 'title',
                    name: 'title'
                },
                {
                    data: 'status',
                    name: 'status',
                    searchable: false
                }
            ],
            order: [
                [1, 'asc']
            ], // Default sorting by title
            pageLength: 20,
            lengthMenu: [
                [20, 50, 100, 500, 1000],
                [20, 50, 100, 500, 1000]
            ],
            dom: '<"card-header"<"head-label"><"dt-action-buttons text-end pt-3 pt-md-0"B>>' +
                '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>' +
                'rt' +
                '<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            buttons: [{
                text: '<i class="bx bx-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">New Menu</span>',
                className: 'btn btn-primary',
                action: function() {
                    window.location.href = "{{ route('website-menus.create') }}";
                }
            }],
            language: {
                processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>',
                emptyTable: 'No menus found',
                zeroRecords: 'No matching menus found'
            },
            responsive: true,
        });

        $('#filter_company_id, #filter_status').on('change', function() {
            table.ajax.reload();
        });
    });
</script>
@endpush