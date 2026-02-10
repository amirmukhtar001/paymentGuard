@extends('layouts.' . config('settings.active_layout'))

@push('stylesheets')
    @include('components.datatables.cdn-styles')
@endpush

@section('content')
    <div class="layout-top-spacing mb-2 m-2">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="card-title mb-0">{{ $title }}</h5>
                    <div>
                        <button type="button" class="btn btn-primary" onclick="window.location='{{ route('settings.pages.create') }}'">
                            <i class="bx bx-plus"></i> New Page
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            @include('components.companies', ['companies' => $companies,'select_id' => 'filter_company_id','label' => 'Website', 'selected_company_id' => $selected_company_id])
                        </div>
                        <div class="col-md-3">
                            <label for="filter_website_section_id" class="form-label">Website Section</label>
                            <select id="filter_website_section_id" class="form-control">
                                <option value="">All Sections</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="filter_status" class="form-label">Status</label>
                            <select id="filter_status" class="form-control">
                                <option value="">All Statuses</option>
                                <option value="draft">Draft</option>
                                <option value="scheduled">Scheduled</option>
                                <option value="published">Published</option>
                                <option value="archived">Archived</option>
                            </select>
                        </div>
                    </div>

                    <div class="alert alert-info" id="reorder-info">
                        <i class="bx bx-info-circle"></i> <strong>Reorder Mode:</strong> Select a website and website section to enable drag-and-drop reordering. Drag the handle icon (<i class="bx bx-grid-vertical"></i>) to reorder pages.
                    </div>
                    <div class="table-responsive">
                        <table id="pages-table" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="60">Order</th>
                                    <th>Actions</th>
                                    <th>Title</th>
                                    <th>Website</th>
                                    <th>Website Section</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @include('components.datatables.cdn-scripts')
    <script>
        $(document).ready(function() {
            let isReordering = false;
            let table = $('#pages-table').DataTable({
                processing: true,
                serverSide: true,
                rowId: 'id',
                ajax: {
                    url: '{{ route('settings.pages.datatable') }}',
                    data: function(d) {
                        d.status = $('#filter_status').val();
                        d.company_id = $('#filter_company_id').val();
                        d.website_section_id = $('#filter_website_section_id').val();
                    }
                },
                rowReorder: {
                    selector: '.reorder-handle',
                    enabled: function() {
                        return $('#filter_website_section_id').val() !== '';
                    },
                    dataSrc: 'id'
                },
                columnDefs: [{
                    targets: 0,
                    orderable: true,
                    searchable: false,
                    className: 'reorder-handle text-center',
                    data: 'sort_order_display'
                }],
                createdRow: function(row, data, dataIndex) {
                    const websiteSectionId = $('#filter_website_section_id').val();
                    const firstCell = $(row).find('td:first');
                    const orderValue = firstCell.html();

                    if (!websiteSectionId) {
                        firstCell.removeClass('reorder-handle').css('cursor', 'default');
                    } else {
                        firstCell.addClass('reorder-handle').css('cursor', 'move');
                        // Add drag icon before the number if not already present
                        if (orderValue && !orderValue.includes('bx-grid-vertical')) {
                            const displayValue = orderValue === '-' ? '-' : orderValue;
                            firstCell.html('<i class="bx bx-grid-vertical text-muted me-1"></i>' + displayValue);
                        }
                    }
                },
                columns: [{
                        data: 'sort_order_display',
                        name: 'sort_order',
                        orderable: true,
                        searchable: false,
                        defaultContent: '-'
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'company_name',
                        name: 'company.title',
                        defaultContent: '-'
                    },
                    {
                        data: 'website_section_name',
                        name: 'websiteSection.heading',
                        defaultContent: '-'
                    },
                    {
                        data: 'status_badge',
                        name: 'status',
                        orderable: true,
                        searchable: false,
                        className: 'text-center'
                    }
                ],
                order: function() {
                    const websiteSectionId = $('#filter_website_section_id').val();
                    if (websiteSectionId) {
                        return [[0, 'asc']]; // Order by sort_order when section is selected
                    }
                    return [[2, 'asc']]; // Order by title when no section selected
                }(),
                pageLength: 20,
                language: {
                    processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>',
                }
            });

            // Load website sections when company changes
            function loadWebsiteSections(companyId) {
                const $sectionSelect = $('#filter_website_section_id');

                if (!companyId) {
                    $sectionSelect.empty().append('<option value="">All Sections</option>');
                    return;
                }

                $sectionSelect.prop('disabled', true).empty().append('<option value="">Loading...</option>');

                $.ajax({
                    url: '{{ route('settings.pages.website-sections') }}',
                    type: 'GET',
                    data: { company_id: companyId },
                    success: function(data) {
                        $sectionSelect.empty().append('<option value="">All Sections</option>');

                        if (data && data.length > 0) {
                            data.forEach(function(section) {
                                $sectionSelect.append('<option value="' + section.id + '">' + section.title + '</option>');
                            });
                        }
                    },
                    error: function() {
                        $sectionSelect.empty().append('<option value="">Error loading sections</option>');
                    },
                    complete: function() {
                        $sectionSelect.prop('disabled', false);
                    }
                });
            }

            // Toggle reorder info and enable/disable rowReorder
            function toggleReorderMode() {
                const websiteSectionId = $('#filter_website_section_id').val();
                if (websiteSectionId) {
                    // Info message stays visible, but can update text if needed
                    table.rowReorder.enable(true);
                    // Show first column (Order column)
                    table.column(0).visible(true);
                } else {
                    // Info message stays visible
                    table.rowReorder.enable(false);
                    // Hide first column (Order column)
                    table.column(0).visible(false);
                }
            }

            // Handle row reorder
            table.on('row-reorder', function(e, diff, edit) {
                if (!diff.length || isReordering) return;

                isReordering = true;
                const websiteSectionId = $('#filter_website_section_id').val();

                if (!websiteSectionId) {
                    isReordering = false;
                    return;
                }

                // Get actual DOM order after drag-and-drop
                const items = [];
                $('#pages-table tbody tr').each(function(index) {
                    const rowData = table.row(this).data();
                    if (rowData && rowData.id) {
                        items.push({
                            id: rowData.id,
                            sort_order: index + 1
                        });
                    }
                });

                $.ajax({
                    url: '{{ route("settings.pages.reorder") }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: {
                        items: items
                    },
                    success: function() {
                        table.ajax.reload(null, false);
                    },
                    error: function(xhr) {
                        console.log('Reorder error:', xhr.responseText);
                        table.ajax.reload(null, false);
                    },
                    complete: function() {
                        isReordering = false;
                    }
                });
            });

            // Load sections when company changes
            $('#filter_company_id').on('change', function() {
                loadWebsiteSections($(this).val());
                $('#filter_website_section_id').val('').trigger('change');
                table.ajax.reload();
            });

            // Reload table when status or website section changes
            $('#filter_status, #filter_website_section_id').on('change', function() {
                toggleReorderMode();
                table.ajax.reload();
            });

            // Load sections on page load if company is selected
            if ($('#filter_company_id').val()) {
                loadWebsiteSections($('#filter_company_id').val());
            }

            // Initialize reorder mode (hide Order column by default)
            table.column(0).visible(false);
            toggleReorderMode();
        });
    </script>
@endpush
