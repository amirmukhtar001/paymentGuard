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
                <div class="header-elements d-flex gap-2 flex-wrap">
                    <button type="button" class="btn btn-outline-primary" id="reorder-sections-btn">
                        <i class="bx bx-move-alt"></i> Reorder Sections
                    </button>
                </div>
            </div>

            <div class="card-body">
                {{-- Filters --}}
                <div class="row mb-3">
                    <div class="col-md-3">
                        <div class="form-group">
                            @include('components.companies', ['companies' => $companies,'select_id' => 'filter_company_id','label' => 'Web Site', 'selected_company_id' => $selected_company_id])
                            <!-- <label for="filter_company_id">Department</label>
                            <select id="filter_company_id" class="form-control">
                                <option value="">All Department</option>
                                @foreach($companies as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select> -->
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="filter_status">Status</label>
                            <select id="filter_status" class="form-control">
                                <option value="">All</option>
                                <option value="draft">Draft</option>
                                <option value="active">Active</option>
                                <option value="archived">Archived</option>
                                <option value="hidden">Hidden</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="table-responsive" style="min-height: 200px">
                    <table id="website-sections-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="width: 160px;">Actions</th>
                                <th>Preview</th>
                                <th>Title</th>
                                <th>Section Type</th>
                                <th>Status</th>
                                <th>Website</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Reorder Modal --}}
<div class="modal fade" id="reorderModal" tabindex="-1" aria-labelledby="reorderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="reorderModalLabel">Reorder Sections</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted">Drag and drop sections to adjust their display order. Filters from the list view are respected.</p>
                <ul class="list-group" id="reorder-list">
                    <li class="list-group-item text-center text-muted">No sections loaded.</li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="save-sort-order">
                    <i class="bx bx-save"></i> Save Order
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@include('components.datatables.cdn-scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        let table = $('#website-sections-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route("settings.website-sections.datatable") }}',
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
                    data: 'preview',
                    name: 'preview',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'title',
                    name: 'website_sections.title',
                    defaultContent: '-'
                },
                {
                    data: 'section_type',
                    name: 'website_sections.section_type',
                    searchable: false
                },
                {
                    data: 'status',
                    name: 'website_sections.status',
                    searchable: false
                },
                {
                    data: 'company_name',
                    name: 'company.title',
                    defaultContent: '-'
                }
            ],
            order: [
                [5, 'asc']
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
                                    return $(node).text().trim().toLowerCase() !== 'actions';
                                }
                            }
                        },
                        {
                            extend: 'csv',
                            text: '<i class="bx bx-file me-1"></i> CSV',
                            className: 'dropdown-item',
                            filename: 'website-sections-' + new Date().toISOString().slice(0, 10)
                        },
                        {
                            extend: 'excel',
                            text: '<i class="bx bxs-file-export me-1"></i> Excel',
                            className: 'dropdown-item',
                            filename: 'website-sections-' + new Date().toISOString().slice(0, 10)
                        }
                    ]
                },
                {
                    text: '<i class="bx bx-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">New Section</span>',
                    className: 'btn btn-primary',
                    action: function() {
                        window.location.href = "{{ route('settings.website-sections.create') }}";
                    }
                }
            ],
            language: {
                processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>',
                emptyTable: 'No sections found',
                zeroRecords: 'No matching sections found'
            },
            responsive: true,
        });

        $('#filter_company_id, #filter_status').on('change', function() {
            table.ajax.reload();
        });

        const reorderModalEl = document.getElementById('reorderModal');
        const reorderModal = new bootstrap.Modal(reorderModalEl);
        const reorderList = document.getElementById('reorder-list');
        let sortableInstance = null;

        function renderReorderList(items) {
            reorderList.innerHTML = '';

            if (!items.length) {
                reorderList.innerHTML = '<li class="list-group-item text-center text-muted">No sections available for the selected filters.</li>';
                return;
            }

            items.forEach(function(section) {
                const badge = `<span class="badge bg-label-info ms-2 text-uppercase">${section.section_type}</span>`;
                const status = `<span class="badge bg-label-secondary ms-2">${section.status}</span>`;
                const title = section.title || '(Untitled Section)';

                reorderList.insertAdjacentHTML('beforeend',
                    `<li class="list-group-item d-flex align-items-center justify-content-between" data-id="${section.id}">
                        <div>
                            <i class="bx bx-grid-vertical drag-handle me-2 text-muted"></i>
                            ${title}
                            ${badge}
                            ${status}
                        </div>
                        <span class="text-muted">#${section.sort_order ?? '-'}</span>
                    </li>`
                );
            });

            if (sortableInstance) {
                sortableInstance.destroy();
            }

            sortableInstance = Sortable.create(reorderList, {
                animation: 150,
                handle: '.drag-handle',
                ghostClass: 'bg-light'
            });
        }

        function loadReorderList() {
            const companyId = $('#filter_company_id').val();

            if (!companyId) {
                reorderList.innerHTML = '<li class="list-group-item text-center text-warning"><i class="bx bx-info-circle me-2"></i><strong>Please select a website first</strong><br><small class="text-muted">Choose a website from the filter above to reorder its sections.</small></li>';
                return;
            }

            reorderList.innerHTML = '<li class="list-group-item text-center text-muted">Loading...</li>';

            $.ajax({
                url: '{{ route("settings.website-sections.orderable") }}',
                data: {
                    company_id: companyId,
                    status: $('#filter_status').val(),
                },
                success: function(response) {
                    renderReorderList(response);
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        reorderList.innerHTML = '<li class="list-group-item text-center text-warning"><i class="bx bx-info-circle me-2"></i><strong>Please select a website first</strong><br><small class="text-muted">Choose a website from the filter above to reorder its sections.</small></li>';
                    } else {
                        reorderList.innerHTML = '<li class="list-group-item text-center text-danger"><i class="bx bx-error-circle me-2"></i>Failed to load sections. Please try again.</li>';
                    }
                }
            });
        }

        $('#reorder-sections-btn').on('click', function() {
            const companyId = $('#filter_company_id').val();

            if (!companyId) {
                // Show message in modal instead of alert
                reorderModal.show();
                reorderList.innerHTML = '<li class="list-group-item text-center text-warning"><i class="bx bx-info-circle me-2"></i><strong>Please select a website first</strong><br><small class="text-muted">Choose a website from the filter above to reorder its sections.</small></li>';
                return;
            }

            reorderModal.show();
            loadReorderList();
        });

        $('#save-sort-order').on('click', function() {
            const companyId = $('#filter_company_id').val();

            if (!companyId) {
                reorderList.innerHTML = '<li class="list-group-item text-center text-warning"><i class="bx bx-info-circle me-2"></i><strong>Please select a website first</strong><br><small class="text-muted">Choose a website from the filter above to reorder its sections.</small></li>';
                return;
            }

            const orderedIds = Array.from(reorderList.querySelectorAll('li[data-id]')).map(function(li) {
                return li.getAttribute('data-id');
            });

            if (!orderedIds.length) {
                alert('No sections to reorder.');
                return;
            }

            const button = this;
            button.disabled = true;
            button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...';

            $.ajax({
                url: '{{ route("settings.website-sections.sort-order") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    company_id: companyId,
                    sections: orderedIds
                },
                success: function(response) {
                    reorderModal.hide();
                    table.ajax.reload(null, false);
                },
                error: function(xhr) {
                    const message = xhr.responseJSON?.message || 'Unable to save the new order. Please try again.';
                    alert(message);
                },
                complete: function() {
                    button.disabled = false;
                    button.innerHTML = '<i class="bx bx-save"></i> Save Order';
                }
            });
        });
    });
</script>
@endpush
