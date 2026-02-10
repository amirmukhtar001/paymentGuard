@extends('layouts.' . config('settings.active_layout'))

@push('stylesheets')
@include('components.datatables.cdn-styles')
<style>
    .draggable-row {
        cursor: move;
    }
    .draggable-row:hover {
        background-color: #f8f9fa;
    }
    .ui-sortable-helper {
        background-color: #fff;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }
</style>
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
                            <label for="filter_status">Status</label>
                            <select id="filter_status" class="form-control">
                                <option value="">All</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        @include('components.companies', ['companies' => $companies, 'select_id' => 'filter_company_id', 'label' => 'Web Site', 'selected_company_id' => $selected_company_id])
                    </div>
                </div>
                <div class="alert alert-info" id="sorting-notice" style="display: none;">
                    <i class="bx bx-info-circle"></i> Please select a website/company to enable drag & drop sorting.
                </div>
                <div class="table-responsive" style="min-height: 200px">
                    <table id="contacts-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="width: 50px;">Sort</th>
                                <th style="width: 160px;">Actions</th>
                                <th>Name</th>
                                <th>Designation</th> 
                                <th>Department</th>
                                <th>Contact #</th>
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
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {

        let table = $('#contacts-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route("settings.contacts.datatable") }}',
                data: function(d) {
                    d.status = $('#filter_status').val();
                    d.company_id = $('#filter_company_id').val();
                    d.is_primary = $('#filter_is_primary').val();
                }
            },
            columns: [
                {
                    data: 'sort_order',
                    name: 'sort_order',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        return '<i class="bx bx-menu" style="cursor: move; font-size: 20px;"></i>';
                    }
                },
                {
                    data: 'actions',
                    name: 'actions',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'designation',
                    name: 'contacts.designation'
                }, 
                {
                    data: 'department_name',
                    name: 'department_name'
                },
                {
                    data: 'contact_number',
                    name: 'contacts.contact_number'
                },
                {
                    data: 'status',
                    name: 'contacts.status',
                    orderable: true,
                    searchable: false
                }
            ],
            order: [
                [2, 'asc'] // Order by name column initially
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
                            className: 'dropdown-item',
                            exportOptions: {
                                columns: function(idx, data, node) {
                                    return $(node).text().trim().toLowerCase() !== 'action' && idx !== 0;
                                }
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
                                columns: function(idx, data, node) {
                                    return $(node).text().trim().toLowerCase() !== 'action' && idx !== 0;
                                }
                            },
                            filename: 'contact-types-' + new Date().toISOString().slice(0, 10)
                        },
                        {
                            extend: 'excel',
                            text: '<i class="bx bxs-file-export me-1"></i> Excel',
                            className: 'dropdown-item',
                            exportOptions: {
                                columns: function(idx, data, node) {
                                    return $(node).text().trim().toLowerCase() !== 'action' && idx !== 0;
                                }
                            },
                            filename: 'contact-types-' + new Date().toISOString().slice(0, 10)
                        },
                        {
                            extend: 'pdfHtml5',
                            text: '<i class="bx bxs-file-pdf me-1"></i> PDF',
                            className: 'dropdown-item',
                            exportOptions: {
                                columns: function(idx, data, node) {
                                    return $(node).text().trim().toLowerCase() !== 'action' && idx !== 0;
                                }
                            },
                            filename: 'contact-types-' + new Date().toISOString().slice(0, 10),
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
                            className: 'dropdown-item',
                            exportOptions: {
                                columns: function(idx, data, node) {
                                    return $(node).text().trim().toLowerCase() !== 'action' && idx !== 0;
                                }
                            }
                        }
                    ]
                },
                {
                    text: '<i class="bx bx-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">New Contact</span>',
                    className: 'btn btn-primary',
                    action: function(e, dt, node, config) {
                        window.location.href = "{{ route('settings.contacts.create') }}";
                    }
                }
            ],
            language: { 
                emptyTable: 'No category types found',
                zeroRecords: 'No matching category types found'
            },
            responsive: true,
            rowCallback: function(row, data) {
                $(row).addClass('draggable-row');
                $(row).attr('data-id', data.id);
            },
            drawCallback: function() {
                checkSortingEligibility();
            }
        });

        function checkSortingEligibility() {
            let companyId = $('#filter_company_id').val();
            
            if (companyId) {
                // Enable sorting
                $('#sorting-notice').hide();
                initSortable();
            } else {
                // Disable sorting
                $('#sorting-notice').show();
                $('#contacts-table tbody').sortable('destroy');
                $('#contacts-table tbody tr').css('cursor', 'default');
            }
        }

        function initSortable() {
            // Destroy existing sortable if it exists
            if ($('#contacts-table tbody').hasClass('ui-sortable')) {
                $('#contacts-table tbody').sortable('destroy');
            }

            $('#contacts-table tbody').sortable({
                handle: '.bx-menu',
                helper: function(e, ui) {
                    ui.children().each(function() {
                        $(this).width($(this).width());
                    });
                    return ui;
                },
                update: function(event, ui) {
                    let companyId = $('#filter_company_id').val();
                    
                    if (!companyId) {
                        alert('Please select a website/company before sorting.');
                        table.ajax.reload();
                        return;
                    }

                    let order = [];
                    $('#contacts-table tbody tr').each(function(index) {
                        let id = $(this).data('id');
                        if (id) {
                            order.push({
                                id: id,
                                sort_order: index + 1
                            });
                        }
                    });

                    $.ajax({
                        url: '{{ route("settings.contacts.update-order") }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            order: order,
                            company_id: companyId
                        },
                        success: function(response) {
                            if (response.success) {
                                console.log('Order updated successfully');
                                // Optional: Show success toast notification
                            }
                        },
                        error: function(xhr) {
                            console.error('Error updating order:', xhr);
                            alert('Failed to update order. Please try again.');
                            table.ajax.reload();
                        }
                    });
                }
            });
        }

        $('#filter_status, #filter_company_id, #filter_is_primary').on('change', function() {
            table.ajax.reload();
        });
    });
</script>
@endpush