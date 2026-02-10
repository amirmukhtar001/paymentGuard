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
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">District</label>
                            <input type="text" class="form-control"
                                value="{{ $district->name ?? $district->title ?? $district_id }}"
                                readonly>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="filter_period_year">Year</label>
                            @php
                            $currentYear = (int) date('Y');
                            $yearVal = $periodYear ?? '';
                            @endphp
                            <select id="filter_period_year" class="form-control">
                                <option value="current" {{ ($yearVal === null || $yearVal === '') ? 'selected' : '' }}>
                                    Current (No year)
                                </option>
                                @for($y = $currentYear; $y >= ($currentYear - 30); $y--)
                                <option value="{{ $y }}" {{ (string)$yearVal === (string)$y ? 'selected' : '' }}>
                                    {{ $y }}
                                </option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <div class="col-md-5 d-flex align-items-end justify-content-end">
                        <a href="{{ route('settings.districts.show', $district_id) }}" class="btn btn-warning me-2">
                            <i class="bx bx-arrow-back tf-icons"></i> Back
                        </a>

                        <a href="{{ route('settings.districts.statistics.create', ['id' => $district_id]) }}"
                            class="btn btn-primary">
                            <i class="bx bx-plus me-sm-1"></i>
                            <span class="d-none d-sm-inline-block">Add / Update Statistics</span>
                        </a>
                    </div>
                </div>

                <div class="table-responsive" style="min-height: 200px">
                    <table id="district-stats-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Stat</th>
                                <th>Key</th>
                                <th>Value</th>
                                <th>Year</th>
                                <th>Updated</th>
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

        let table = $('#district-stats-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route("settings.districts.statistics.datatable", $district_id) }}',
                data: function(d) {
                    d.period_year = $('#filter_period_year').val(); // current | 2025 etc
                }
            },
            columns: [{
                    data: 'stat_label',
                    name: 'statDefinition.label'
                },
                {
                    data: 'stat_key',
                    name: 'statDefinition.key'
                },
                {
                    data: 'value',
                    name: 'value_number',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'period_year',
                    name: 'district_stat_values.period_year',
                    searchable: false
                },
                {
                    data: 'updated_at',
                    name: 'district_stat_values.updated_at',
                    searchable: false
                },
            ],
            order: [
                [4, 'desc']
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
                            className: 'dropdown-item'
                        },
                        {
                            extend: 'csv',
                            text: '<i class="bx bx-file me-1"></i> CSV',
                            className: 'dropdown-item',
                            filename: 'district-stats-' + new Date().toISOString().slice(0, 10)
                        },
                        {
                            extend: 'excel',
                            text: '<i class="bx bxs-file-export me-1"></i> Excel',
                            className: 'dropdown-item',
                            filename: 'district-stats-' + new Date().toISOString().slice(0, 10)
                        },
                        {
                            extend: 'pdfHtml5',
                            text: '<i class="bx bxs-file-pdf me-1"></i> PDF',
                            className: 'dropdown-item',
                            filename: 'district-stats-' + new Date().toISOString().slice(0, 10),
                            orientation: 'portrait',
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
                            className: 'dropdown-item'
                        }
                    ]
                },
                {
                    text: '<i class="bx bx-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">Add / Update Statistics</span>',
                    className: 'btn btn-primary',
                    action: function() {
                        const year = $('#filter_period_year').val();
                        let url = "{{ route('settings.districts.statistics.create', ['id' => $district_id]) }}";

                        // pass year to form as query param
                        if (year && year !== 'current') {
                            url += '?period_year=' + encodeURIComponent(year);
                        }
                        window.location.href = url;
                    }
                }
            ],
            language: {
                emptyTable: 'No statistics found',
                zeroRecords: 'No matching statistics found'
            },
            responsive: true,
        });

        $('#filter_period_year').on('change', function() {
            table.ajax.reload();
        });

    });
</script>
@endpush