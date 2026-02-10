@extends('layouts.app_screen_db')

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Model Filters</h4>
                @can('settings.model.filters.create')
                    <a href="{{ route('settings.model.filters.create') }}" class="btn btn-primary">
                        <i class="bx bx-plus"></i> Add New Filter
                    </a>
                @endcan
            </div>
            <div class="card-body">

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-striped" id="model-filters-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Filter For</th>
                                <th>Model</th>
                                <th>Filter Options</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this filter?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
            </div>
        </div>
    </div>
</div>

<!-- Hard Delete Confirmation Modal -->
<div class="modal fade" id="hardDeleteModal" tabindex="-1" aria-labelledby="hardDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="hardDeleteModalLabel">Confirm Permanent Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <strong>Warning:</strong> This action cannot be undone! Are you sure you want to permanently delete this filter?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmHardDelete">Permanently Delete</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
    @include('components.datatables.cdn-styles')
@endpush

@push('scripts')
    @include('components.datatables.cdn-scripts')
<script>
$(document).ready(function() {
    // Check if DataTable is available
    if (typeof $.fn.DataTable === 'undefined') {
        console.error('DataTable is not loaded');
        return;
    }

    var table = $('#model-filters-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('settings.model.filters.datatable') }}",
            type: 'GET'
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'filter_for', name: 'filter_for', orderable: false, searchable: false },
            { data: 'model', name: 'model', orderable: false, searchable: false },
            { data: 'filter_options', name: 'filter_options', orderable: false, searchable: false },
            { data: 'status', name: 'is_active' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ],
        order: [[0, 'desc']],
        pageLength: 25,
        responsive: true,
        language: {
            processing: "Loading..."
        }
    });

    // Soft Delete
    var deleteId = null;
    $(document).on('click', '.delete-btn', function() {
        deleteId = $(this).data('id');
        $('#deleteModal').modal('show');
    });

    $('#confirmDelete').click(function() {
        if (deleteId) {
            $.ajax({
                url: '/settings/model-filters/' + deleteId,
                type: 'DELETE',
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    $('#deleteModal').modal('hide');
                    table.ajax.reload();
                    toastr.success('Filter deleted successfully');
                },
                error: function(xhr) {
                    $('#deleteModal').modal('hide');
                    toastr.error('Error deleting filter');
                }
            });
        }
    });

    // Hard Delete
    var hardDeleteId = null;
    $(document).on('click', '.hard-delete-btn', function() {
        hardDeleteId = $(this).data('id');
        $('#hardDeleteModal').modal('show');
    });

    $('#confirmHardDelete').click(function() {
        if (hardDeleteId) {
            $.ajax({
                url: '/settings/model-filters/' + hardDeleteId + '/force-delete',
                type: 'DELETE',
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    $('#hardDeleteModal').modal('hide');
                    table.ajax.reload();
                    toastr.success('Filter permanently deleted');
                },
                error: function(xhr) {
                    $('#hardDeleteModal').modal('hide');
                    toastr.error('Error permanently deleting filter');
                }
            });
        }
    });

    // Restore
    $(document).on('click', '.restore-btn', function() {
        var restoreId = $(this).data('id');
        $.ajax({
            url: '/settings/model-filters/' + restoreId + '/restore',
            type: 'POST',
            data: {
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                table.ajax.reload();
                toastr.success('Filter restored successfully');
            },
            error: function(xhr) {
                toastr.error('Error restoring filter');
            }
        });
    });
});
</script>
@endpush
