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
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="filter_department_id" class="form-label">Department</label>
                            <select id="filter_department_id" class="form-control">
                                <option value="">All Departments</option>
                                @foreach($departments as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="feedbacks-table" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Actions</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Department</th>
                                    <th>Feedback</th>
                                    <th>Replied</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Reply Modal --}}
    <div class="modal fade" id="replyModal" tabindex="-1" aria-labelledby="replyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="replyModalLabel">Reply to Feedback</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="replyForm">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="reply_feedback_uuid" name="feedback_uuid" value="">
                        <div class="mb-3">
                            <label class="form-label">From</label>
                            <p class="form-control-plaintext" id="modal_name">-</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <p class="form-control-plaintext" id="modal_email">-</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Feedback</label>
                            <div class="border rounded p-2 bg-light" id="modal_feedback">-</div>
                        </div>
                        <div class="mb-3">
                            <label for="reply_text" class="form-label">Reply <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="reply_text" name="reply" rows="5" required placeholder="Enter your reply..."></textarea>
                            <div class="invalid-feedback" id="reply_error"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="replySubmitBtn">Save Reply</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @include('components.datatables.cdn-scripts')
    <script>
        $(document).ready(function() {
            let table = $('#feedbacks-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('settings.feedbacks.datatable') }}',
                    data: function(d) {
                        d.department_id = $('#filter_department_id').val();
                    }
                },
                columns: [
                    { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-center' },
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    { data: 'department_name', name: 'department.name', defaultContent: '-' },
                    { data: 'feedback', name: 'feedback', render: function(data) { return data ? (data.length > 80 ? data.substring(0, 80) + '...' : data) : '-'; } },
                    { data: 'reply', name: 'reply', render: function(data) { return data ? '<span class="badge bg-label-success">Yes</span>' : '<span class="badge bg-label-secondary">No</span>'; }, className: 'text-center' },
                    { data: 'created_at', name: 'created_at', render: function(data) {
                        if (!data) return '-';
                        var d = new Date(data);
                        var day = ('0' + d.getDate()).slice(-2);
                        var month = ('0' + (d.getMonth() + 1)).slice(-2);
                        return day + ' ' + month + ' ' + d.getFullYear();
                    } }
                ],
                order: [[6, 'desc']],
                pageLength: 20,
                language: {
                    processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>',
                }
            });

            $('#filter_department_id').on('change', function() {
                table.ajax.reload();
            });

            const replyModal = new bootstrap.Modal(document.getElementById('replyModal'));

            $(document).on('click', '.btn-reply', function() {
                const uuid = $(this).data('uuid');
                const name = $(this).data('name');
                const email = $(this).data('email');
                const feedback = $(this).data('feedback');
                const reply = $(this).data('reply') || '';

                $('#reply_feedback_uuid').val(uuid);
                $('#modal_name').text(name || '-');
                $('#modal_email').text(email || '-');
                $('#modal_feedback').text(feedback || '-');
                $('#reply_text').val(reply);
                $('#reply_text').removeClass('is-invalid');
                $('#reply_error').text('');
                replyModal.show();
            });

            $('#replyForm').on('submit', function(e) {
                e.preventDefault();
                const uuid = $('#reply_feedback_uuid').val();
                const reply = $('#reply_text').val().trim();
                const $submitBtn = $('#replySubmitBtn');
                const $replyInput = $('#reply_text');

                if (!reply) {
                    $replyInput.addClass('is-invalid');
                    $('#reply_error').text('Reply is required.');
                    return;
                }

                $submitBtn.prop('disabled', true);
                $.ajax({
                    url: '{{ route('settings.feedbacks.reply', ['feedback' => '__UUID__']) }}'.replace('__UUID__', uuid),
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        reply: reply
                    },
                    success: function(res) {
                        replyModal.hide();
                        table.ajax.reload();
                        $submitBtn.prop('disabled', false);
                        if (typeof toastr !== 'undefined') {
                            toastr.success(res.message || 'Reply saved successfully.');
                        } else {
                            alert(res.message || 'Reply saved successfully.');
                        }
                    },
                    error: function(xhr) {
                        $submitBtn.prop('disabled', false);
                        const msg = xhr.responseJSON && (xhr.responseJSON.message || (xhr.responseJSON.errors && xhr.responseJSON.errors.reply && xhr.responseJSON.errors.reply[0]));
                        $replyInput.addClass('is-invalid');
                        $('#reply_error').text(msg || 'Failed to save reply.');
                    }
                });
            });
        });
    </script>
@endpush
