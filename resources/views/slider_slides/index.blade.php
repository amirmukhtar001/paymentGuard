@extends('layouts.' . config('settings.active_layout'))

@push('stylesheets')
@include('components.datatables.cdn-styles')
@endpush
@php #filter_company_id
$sqliderQuery = null;

if(isset($uuid))
{
$sqliderQuery = '?q='.$uuid;
}
@endphp

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">

            <div class="card-body">
                <div class="row mb-3">

                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="form-group">
                                @include('components.companies', ['companies' => $companies, 'select_id' => 'filter_company_id', 'label' => 'Web Site', 'selected_company_id' => $selected_company_id])
                                {{--
                                <label for="filter_company_id">Web Sites</label>
                                <select id="filter_company_id" class="form-control">
                                    <option value="">All Web Sites</option>
                                    @foreach($companies as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                                </select>--}}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="filter_slider_id">Slider</label>
                            <select id="filter_slider_id" class="form-control"  {{ isset($sliderId) ? 'disabled' : '' }}>
                                <option value="">All Sliders</option>
                                @foreach($sliders as $id => $name)
                                <option value="{{ $id }}" {{ isset($sliderId) && $sliderId == $id ? 'selected' : '' }}> {{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="slider-slides-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="width: 40px;">Reorder</th>
                                <th>Actions</th>
                                <th>Title</th>
                                <th>Slider</th>
                                <th>Web Site</th>
                                <th>Media</th>
                                <th>Status</th>
                                <th>Sort Order</th>
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
        let table = $('#slider-slides-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route("settings.slider_slides.datatable") }}',
                data: function(d) {
                    d.company_id = $('#filter_company_id').val(); // ⬅ NEW
                    d.slider_id = $('#filter_slider_id').val();
                }
            },
            rowReorder: {
                dataSrc: 'sort_order',
                selector: '.reorder-handle'
            },
            columns: [{
                    data: null,
                    orderable: false,
                    searchable: false,
                    className: 'reorder-handle text-center',
                    render: function() {
                        return '<div class="drag-icon-wrapper" title="Drag to reorder">' +
                            '<i class="bx bx-up-arrow-alt"></i>' +
                            '<i class="bx bx-menu" style="font-size: 1rem;"></i>' +
                            '<i class="bx bx-down-arrow-alt"></i>' +
                            '</div>';
                    }
                },
                {
                    data: 'actions',
                    name: 'actions',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'title',
                    name: 'slider_slides.title'
                },
                {
                    data: 'slider_title',
                    name: 'slider.title'
                },
                {
                    data: 'slider_site_title',
                    name: 'company.title'
                },
                {
                    data: 'media',
                    name: 'media'
                },
                {
                    data: 'is_active',
                    name: 'slider_slides.is_active'
                },
                {
                    data: 'sort_order',
                    name: 'slider_slides.sort_order'
                },
            ],
            order: [
                [7, 'asc']
            ], // Order by sort_order column
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
                text: '<i class="bx bx-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">New Slide</span>',
                className: 'btn btn-primary',
                action: function() {
                    window.location.href = "{{ route('settings.slider_slides.create').$sqliderQuery }}";
                }
            }],
            language: {
                processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>',
                emptyTable: 'No slider slides found',
                zeroRecords: 'No matching slider slides found'
            },
        });

        // ------------- DEPENDENT DROPDOWNS ------------------

        // When company changes: reload sliders list AND reload table
        $('#filter_company_id').on('change', function() {
            let companyId = $(this).val();

            // Reload sliders for this company
            $.ajax({
                url: '{{ route("settings.sliders.by_company") }}', // ⬅ route we’ll add below
                method: 'GET',
                data: {
                    company_id: companyId
                },
                success: function(sliders) {
                    let $sliderSelect = $('#filter_slider_id');
                    $sliderSelect.empty();
                    $sliderSelect.append('<option value="">All Sliders</option>');

                    sliders.forEach(function(slider) {
                        $sliderSelect.append(
                            '<option value="' + slider.id + '">' + slider.name + '</option>'
                        );
                    });

                    // After refreshing sliders, reload the table for new company
                    table.ajax.reload();
                },
                error: function(xhr) {
                    console.error('Error loading sliders by company', xhr);
                    // Still reload table with whatever we have
                    table.ajax.reload();
                }
            });
        });



        // Handle row reorder
        table.on('row-reorder', function(e, diff, edit) {
            if (diff.length === 0) {
                return;
            }
            let reorderData = [];
            // Collect all the changes
            for (let i = 0; i < diff.length; i++) {
                let rowData = table.row(diff[i].node).data();
                reorderData.push({
                    id: rowData.id,
                    sort_order: diff[i].newPosition + 1
                });
            }

            // Send AJAX request to update sort order
            $.ajax({
                url: '{{ route("settings.slider_slides.reorder") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    reorder: reorderData
                },
                success: function(response) {
                    if (response.success) {
                        // Show success message (optional)
                        // toastr.success('Sort order updated successfully');

                        // Reload table to reflect changes
                        table.ajax.reload(null, false);
                    }
                },
                error: function(xhr) {
                    console.error('Error updating sort order:', xhr);
                    // Show error message (optional)
                    // toastr.error('Failed to update sort order');

                    // Reload table to revert changes
                    table.ajax.reload(null, false);
                }
            });
        });

        $('#filter_slider_id').on('change', function() {
            table.ajax.reload();
        });
    });
</script>
@endpush