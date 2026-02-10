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
                        <div class="form-group">
                            <label for="filter_type">Type</label>
                            <select id="filter_type" class="form-control">
                                <option value="">All</option>
                                <option value="integer">Integer</option>
                                <option value="decimal">Decimal</option>
                                <option value="percent">Percent</option>
                                <option value="text">Text</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="filter_active">Active</label>
                            <select id="filter_active" class="form-control">
                                <option value="">All</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="table-responsive" style="min-height: 200px">
                    <table id="stat-definitions-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="width:160px;">Actions</th>
                                <th>Label</th>
                                <th>Key</th>
                                <th>Type</th>
                                <th>Unit</th>
                                <th>Required</th>
                                <th>Active</th>
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

        let table = $('#stat-definitions-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route("settings.stat-definitions.datatable") }}',
                data: function(d) {
                    d.type = $('#filter_type').val();
                    d.active = $('#filter_active').val();
                }
            },
            columns: [{
                    data: 'actions',
                    name: 'actions',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'label',
                    name: 'stat_definitions.label'
                },
                {
                    data: 'key',
                    name: 'stat_definitions.key'
                },
                {
                    data: 'type',
                    name: 'stat_definitions.type'
                },
                {
                    data: 'unit',
                    name: 'stat_definitions.unit',
                    defaultContent: '-'
                },
                {
                    data: 'required_badge',
                    name: 'stat_definitions.is_required',
                    searchable: false,
                    orderable: false
                },
                {
                    data: 'active_badge',
                    name: 'stat_definitions.is_active',
                    searchable: false,
                    orderable: false
                },
                {
                    data: 'sort_order',
                    name: 'stat_definitions.sort_order'
                },
            ],
            order: [
                [7, 'asc']
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
                text: '<i class="bx bx-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">New Stat</span>',
                className: 'btn btn-primary',
                action: function() {
                    window.location.href = "{{ route('settings.stat-definitions.create') }}";
                }
            }],
            language: {
                emptyTable: 'No statistic definitions found',
                zeroRecords: 'No matching definitions found'
            },
            responsive: true,
        });

        $('#filter_type, #filter_active').on('change', function() {
            table.ajax.reload();
        });

    });
</script>
@endpush