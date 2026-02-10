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
                    <button type="button"
                        class="btn btn-success"
                        id="btn-add-domain">
                        <i class="bx bx-plus"></i> Add Domain
                    </button>
                </div>
            </div>

            <div class="card-body">

                {{-- Filters --}}
                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="filter_company_id">Department</label>
                            <select id="filter_company_id" class="form-control">
                                <option value="">All Department</option>
                                @foreach($companies as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="filter_is_primary">Primary?</label>
                            <select id="filter_is_primary" class="form-control">
                                <option value="">All</option>
                                <option value="1">Primary only</option>
                                <option value="0">Non-primary</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="table-responsive" style="min-height: 200px">
                    <table id="website-domains-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="width: 140px;">Actions</th>
                                <th>Host</th>
                                <th>Primary</th>
                                <th>Department</th>
                            </tr>
                        </thead>
                    </table>
                </div>

            </div>
        </div>

    </div>
</div>

{{-- Modal --}}
<div class="modal fade" id="websiteDomainModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="website-domain-form">
            @csrf
            <input type="hidden" id="domain_uuid" name="domain_uuid">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="websiteDomainModalTitle">Add Domain</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div id="form-errors" class="alert alert-danger d-none mb-3"></div>

                    <div class="mb-3">
                        <label class="form-label req">Department</label>
                        <select name="company_id" id="company_id" class="form-control" required>
                            <option value="">Select Department</option>
                            @foreach($companies as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label req">Host</label>
                        <input type="text" name="host" id="host" class="form-control" required>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox"
                            name="is_primary"
                            id="is_primary"
                            value="1"
                            class="form-check-input">
                        <label class="form-check-label" for="is_primary">Is Primary Domain</label>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-success" id="btn-save-domain">
                        <i class="bx bx-save"></i> Save
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
@include('components.datatables.cdn-scripts')

<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        let table = $('#website-domains-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route("settings.website_domains.datatable") }}',
                data: function(d) {
                    d.company_id = $('#filter_company_id').val();
                    d.is_primary = $('#filter_is_primary').val();
                }
            },
            columns: [{
                    data: 'actions',
                    name: 'actions',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'host',
                    name: 'website_domains.host'
                },
                {
                    data: 'is_primary',
                    name: 'website_domains.is_primary',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'company_name',
                    name: 'company.title',
                    defaultContent: '-'
                }
            ],
            order: [
                [1, 'desc']
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
                                    return $(node).text().trim().toLowerCase() !== 'actions';
                                }
                            },
                            filename: 'website-domains-' + new Date().toISOString().slice(0, 10)
                        },
                        {
                            extend: 'excel',
                            text: '<i class="bx bxs-file-export me-1"></i> Excel',
                            className: 'dropdown-item',
                            exportOptions: {
                                columns: function(idx, data, node) {
                                    return $(node).text().trim().toLowerCase() !== 'actions';
                                }
                            },
                            filename: 'website-domains-' + new Date().toISOString().slice(0, 10)
                        },
                        {
                            extend: 'pdfHtml5',
                            text: '<i class="bx bxs-file-pdf me-1"></i> PDF',
                            className: 'dropdown-item',
                            exportOptions: {
                                columns: function(idx, data, node) {
                                    return $(node).text().trim().toLowerCase() !== 'actions';
                                }
                            },
                            filename: 'website-domains-' + new Date().toISOString().slice(0, 10),
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
                                    return $(node).text().trim().toLowerCase() !== 'actions';
                                }
                            }
                        }
                    ]
                },
                {
                    text: '<i class="bx bx-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">New Domain</span>',
                    className: 'btn btn-primary',
                    action: function() {
                        $('#btn-add-domain').trigger('click');
                    }
                }
            ],
            language: {
                processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>',
                emptyTable: 'No website domains found',
                zeroRecords: 'No matching website domains found'
            },
            responsive: true,
        });

        $('#filter_company_id, #filter_is_primary').on('change', function() {
            table.ajax.reload();
        });

        const modalElement = document.getElementById('websiteDomainModal');
        const modal = new bootstrap.Modal(modalElement);

        function resetForm() {
            $('#website-domain-form')[0].reset();
            $('#domain_uuid').val('');
            $('#form-errors').addClass('d-none').html('');
            $('#website-domain-form').find('input[name="_method"]').remove();
        }

        // Open modal for create
        $('#btn-add-domain').on('click', function() {
            resetForm();
            $('#websiteDomainModalTitle').text('Add Domain');
            $('#website-domain-form').attr('action', '{{ route("settings.website_domains.store") }}');
            modal.show();
        });

        // Open modal for edit
        $(document).on('click', '.btn-edit-domain', function() {
            resetForm();
            const uuid = $(this).data('uuid');

            $('#websiteDomainModalTitle').text('Edit Domain');

            $.get('{{ route("settings.website_domains.edit", ":uuid") }}'.replace(':uuid', uuid), function(response) {
                const data = response.data;
                $('#domain_uuid').val(data.uuid);
                $('#company_id').val(data.company_id);
                $('#host').val(data.host);
                $('#is_primary').prop('checked', !!data.is_primary);

                // Set form action & method
                $('#website-domain-form').attr('action', '{{ route("settings.website_domains.update", ":uuid") }}'.replace(':uuid', uuid));
                $('#website-domain-form').append('<input type="hidden" name="_method" value="PUT">');

                modal.show();
            });
        });

        // Submit form via AJAX
        $('#website-domain-form').on('submit', function(e) {
            e.preventDefault();
            const $form = $(this);
            const action = $form.attr('action');
            const method = $form.find('input[name="_method"]').val() || 'POST';
            const formData = $form.serialize();

            $('#form-errors').addClass('d-none').html('');

            $.ajax({
                url: action,
                type: method,
                data: formData,
                success: function(response) {
                    modal.hide();
                    table.ajax.reload(null, false);
                    alert(response.message || 'Saved successfully.');
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors || {};
                        let html = '<ul>';
                        $.each(errors, function(field, messages) {
                            messages.forEach(function(m) {
                                html += '<li>' + m + '</li>';
                            });
                        });
                        html += '</ul>';
                        $('#form-errors').removeClass('d-none').html(html);
                    } else {
                        alert('An error occurred. Please try again.');
                    }
                }
            });
        });

    });
</script>
@endpush