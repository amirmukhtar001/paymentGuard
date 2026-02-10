{{-- resources/views/employees/show.blade.php --}}
@extends('layouts.' . config('settings.active_layout'))

@section('content') 

@php
    $pic = $employee->picture ?? $employee->media ?? null;
    $picExt = strtolower($pic->extension ?? '');
    $picUrl = $pic && $pic->file_path ? asset('storage/' . $pic->file_path) : null;
    $picIsImage = in_array($picExt, ['jpg','jpeg','png','gif','webp','svg']);

    // Prefer latest history as current
    $currentHistory = $employee->currentHistory ?? ($employee->histories?->sortByDesc('working_from')->first());
    $currentFrom = $currentHistory->working_from ?? $employee->working_since ?? null;
    $currentTill = $currentHistory->working_till ?? $employee->worked_till ?? null;

    $currentCader = $currentHistory->cader ?? ($employee->cader?->value ?? $employee->cader ?? '-');
    $currentBps = $currentHistory->bps ?? ($employee->bps?->value ?? $employee->bps ?? '-');
    $currentDesignation = $currentHistory->designation ?? ($employee->designation?->value ?? $employee->designation ?? '-');

    $currentDeptName = $currentHistory->department->name ?? $employee->department->name ?? '-';
    $currentPosTypeName = $currentHistory->positionType->name ?? $employee->positionType->name ?? '-';

    $notif = $currentHistory->notificationMedia ?? $employee->notificationMedia ?? null;
    $notifExt = strtolower($notif->extension ?? '');
    $notifUrl = $notif && $notif->file_path ? asset('storage/' . $notif->file_path) : null;
    $notifIsImage = in_array($notifExt, ['jpg','jpeg','png','gif','webp','svg']);
@endphp

<div class="row">
    <div class="col-12">
        <div class="card">

            <div class="card-header header-elements-inline d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">{{ $title ?? 'Employee Details' }}</h5>

                <div class="header-elements">
                    <a href="{{ route('settings.employees.list') }}" class="btn btn-warning btn-sm">
                        <i class="bx bx-arrow-back tf-icons"></i> Back
                    </a>

                    <a href="{{ route('settings.employees.edit', $employee->uuid) }}" class="btn btn-primary ml-2 btn-sm">
                        <i class="bx bx-edit"></i> Edit
                    </a>
                </div>
            </div>

            <div class="card-body">

                {{-- TOP SUMMARY (profile style like cabinet member) --}}
                <div class="row align-items-start">
                    <div class="col-md-9">
                        <h4 class="mb-2">{{ $employee->name }}</h4>

                        <div class="mb-2">
                            <span class="badge badge-info">
                                {{ $employee->display_on_home?->value ?? $employee->display_on_home ?? '-' }}
                            </span>

                            <span class="badge badge-secondary">
                                Sort: {{ $employee->sort_order ?? 0 }}
                            </span>

                            @if($employee->company)
                                <span class="badge badge-primary">
                                    {{ $employee->company->name }}
                                </span>
                            @endif
                        </div>

                        <div class="text-muted">
                            <strong>Current:</strong>
                            {{ $currentDesignation ?? '-' }}
                            @if($currentDeptName && $currentDeptName !== '-')
                                — {{ $currentDeptName }}
                            @endif
                            @if($currentPosTypeName && $currentPosTypeName !== '-')
                                ({{ $currentPosTypeName }})
                            @endif
                        </div>
                    </div>

                    <div class="col-md-3 text-md-right">
                        @if($picUrl)
                            @if($picIsImage)
                                <img src="{{ $picUrl }}"
                                     alt="{{ $pic->title ?? $employee->name }}"
                                     class="img-thumbnail"
                                     style="max-width: 160px; max-height: 160px; object-fit: cover;">
                            @else
                                <a href="{{ $picUrl }}" target="_blank" rel="noopener">
                                    {{ $pic->title ?? 'View file' }} ({{ strtoupper($picExt) }})
                                </a>
                            @endif
                        @else
                            <div class="text-muted">No Profile Picture</div>
                        @endif
                    </div>
                </div>

                <hr>

                {{-- PROFILE + CURRENT INFO TABLES --}}
                <div class="row">
                    <div class="col-md-6">
                        <h5>Profile Information</h5>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <tbody>
                                    <tr>
                                        <th style="width:35%;">Name</th>
                                        <td>{{ $employee->name ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Web Site</th>
                                        <td>{{ $employee->company->title ?? ($employee->company_id ?? '-') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Display on Home</th>
                                        <td>{{ $employee->display_on_home?->value ?? $employee->display_on_home ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Sort Order</th>
                                        <td>{{ $employee->sort_order ?? 0 }}</td>
                                    </tr>
                                    <!-- <tr>
                                        <th>Profile Picture</th>
                                        <td>
                                            @if($picUrl)
                                                <a href="{{ $picUrl }}" target="_blank" rel="noopener">View</a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr> -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h5>Current Assignment</h5>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <tbody>
                                    <tr>
                                        <th style="width:35%;">Cader</th>
                                        <td>{{ $currentCader ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>BPS</th>
                                        <td>{{ $currentBps ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Designation</th>
                                        <td>{{ $currentDesignation ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Department</th>
                                        <td>{{ $currentDeptName ?? '-' }}</td>
                                    </tr>
                                    <!-- <tr>
                                        <th>Position Type</th>
                                        <td>{{ $currentPosTypeName ?? '-' }}</td>
                                    </tr> -->
                                    <tr>
                                        <th>Working From</th>
                                        <td>
                                            @if($currentFrom)
                                                {{ \Illuminate\Support\Carbon::parse($currentFrom)->format('d M Y') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Working Till</th>
                                        <td>
                                            @if($currentTill)
                                                {{ \Illuminate\Support\Carbon::parse($currentTill)->format('d M Y') }}
                                            @else
                                                <span class="badge bg-label-success rounded p-2">Present</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Notification / Order</th>
                                        <td>
                                            @if($notifUrl)
                                                @if($notifIsImage)
                                                    <a href="{{ $notifUrl }}" target="_blank" rel="noopener">View</a>
                                                @else
                                                    <a href="{{ $notifUrl }}" target="_blank" rel="noopener">
                                                        {{ $notif->title ?? 'View file' }} ({{ strtoupper($notifExt) }})
                                                    </a>
                                                @endif
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        {{-- Optional: show notification image preview like form --}}
                        @if($notifUrl && $notifIsImage)
                            <div class="mt-2">
                                <img src="{{ $notifUrl }}"
                                     alt="{{ $notif->title ?? 'Notification' }}"
                                     class="img-thumbnail"
                                     style="max-width: 220px; max-height: 220px; object-fit: cover;">
                            </div>
                        @endif
                    </div>
                </div>

                <hr>

                {{-- MESSAGE / QUOTE --}}
                <div class="row">
                    <div class="col-12">
                        <h5>Message / Quote</h5>

                        @if(!empty($employee->message))
                            <div class="border rounded p-3">
                                {!! $employee->message !!}
                            </div>
                        @else
                            <div class="text-muted">No message added.</div>
                        @endif
                    </div>
                </div>

                <hr>

                {{-- EMPLOYMENT HISTORY --}}
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="mb-0">Employment History</h5>

                    <button type="button" class="btn btn-success" id="btnAddHistory">
                        <i class="bx bx-plus"></i> Add History
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table table-sm table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Designation</th>
                                <th>Department</th> 
                                <th>From</th>
                                <th>Till</th>
                                
                                <th>Attachment</th>
                                <th style="width:140px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="employeeHistoryTbody">
                            @foreach(($employee->histories ?? collect()) as $h)
                                <tr id="hist-row-{{ $h->id }}">
                                    <td>{{ $h->serviceDesignation->title ?? '-' }}</td>
                                    <td>{{ $h->department->name ?? '-' }}</td> 
                                    <td>{{ $h->working_from ? $h->working_from->format('d M Y') : '-' }}</td>
                                    <td>
                                        @if($h->working_till)
                                            {{ $h->working_till->format('d M Y') }}
                                        @else
                                            <span class="badge bg-label-success rounded p-2">Present</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $notif = $h->notificationMedia ?? $h->notificationMedia ?? null;
                                            $notifExt = strtolower($notif->extension ?? '');
                                            $notifUrl = $notif && $notif->file_path ? asset('storage/' . $notif->file_path) : null;
                                            $notifIsImage = in_array($notifExt, ['jpg','jpeg','png','gif','webp','svg']);
                                            @endphp 
                                            @if($notifUrl)
                                                @if($notifIsImage)
                                                    <a href="{{ $notifUrl }}" target="_blank" rel="noopener">View</a>
                                                @else
                                                    <a href="{{ $notifUrl }}" target="_blank" rel="noopener">
                                                        {{ $notif->title ?? 'View file' }} ({{ strtoupper($notifExt) }})
                                                    </a>
                                                @endif
                                            @else
                                                -
                                            @endif
                                    </td>
                                    <td>
                                        <button type="button"
                                                class="btn btn-sm btn-primary btnEditHistory"
                                                data-history-id="{{ $h->id }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button"
                                                class="btn btn-sm btn-danger btnDeleteHistory"
                                                data-history-id="{{ $h->id }}">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>



@include('employees.partials.history-modal')

@include('components.media-manager', ['companies' => $companies])
@endsection

@push('scripts')
<script>
$(document).ready(function () {

    // Init select2 globally
    if ($.fn.select2) {
        $('.select2').select2();
    }

    // select2 inside modal
    $('#historyModal').on('shown.bs.modal', function () {
        if ($.fn.select2) {
            $(this).find('.select2').select2({ dropdownParent: $('#historyModal') });
        }
    });

    function showModalBs4Or5() {
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            bootstrap.Modal.getOrCreateInstance(document.getElementById('historyModal')).show();
        } else {
            $('#historyModal').modal('show');
        }
    }

    function hideModalBs4Or5() {
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            bootstrap.Modal.getOrCreateInstance(document.getElementById('historyModal')).hide();
        } else {
            $('#historyModal').modal('hide');
        }
    }

    function resetHistoryModal() {
        $('#historyModalTitle').text('Add History');
        $('#historySubmitBtn').text('Save');
        $('#history_form_method').val('POST');
        $('#history_id').val('');
        $('#historyFormErrors').addClass('d-none').html('');

        $('#modal_cader').val('').trigger('change');
        $('#modal_bps').val('').trigger('change');
        $('#modal_designation').val('').trigger('change');

        $('#modal_department_id').val('').trigger('change');
        $('#modal_position_type_id').val('').trigger('change');

        $('#modal_working_from').val('');
        $('#modal_working_till').val('');

        $('#modal_notification_media_id').val('');
        $('#modal_notification_media_preview').html('<span class="text-muted">No media selected</span>');
    }

    function renderMediaPreview(url, ext, title) {
        if (!url) return '<span class="text-muted">No media selected</span>';
        ext = (ext || '').toLowerCase();
        const isImage = ['jpg','jpeg','png','gif','webp','svg'].includes(ext);

        if (isImage) {
            return `
                <div>
                    <img src="${url}" class="img-thumbnail" style="max-width:150px;max-height:150px;object-fit:cover;">
                    <div class="small text-muted mt-1">${title || ''}</div>
                </div>
            `;
        }
        return `<a href="${url}" target="_blank" rel="noopener">${title || 'View file'} (${ext.toUpperCase()})</a>`;
    }

    // ADD
    $('#btnAddHistory').on('click', function () {
        resetHistoryModal();
        showModalBs4Or5();
    });

    // EDIT
    $(document).on('click', '.btnEditHistory', function () {
        const id = $(this).data('history-id');
        resetHistoryModal();

        $('#historyModalTitle').text('Edit History');
        $('#historySubmitBtn').text('Update');
        $('#history_form_method').val('PUT');
        $('#history_id').val(id);

        const url = `{{ route('settings.employees.histories.show', ['employee' => $employee->uuid, 'history' => '___ID___']) }}`
            .replace('___ID___', id);

        $.get(url).done(function(res){
            if(!res.success) return;
            const d = res.data;

            $('#modal_cader').val(d.cader || '').trigger('change');
            $('#modal_bps').val(d.bps || '').trigger('change');
            $('#modal_designation').val(d.designation || '').trigger('change');

            $('#modal_department_id').val(d.department_id || '').trigger('change');
            $('#modal_position_type_id').val(d.position_type_id || '').trigger('change');

            $('#modal_working_from').val(d.working_from || '');
            $('#modal_working_till').val(d.working_till || '');

            $('#modal_notification_media_id').val(d.notification_media_uuid || '');
            $('#modal_notification_media_preview').html(
                renderMediaPreview(d.notification_media_url, d.notification_media_ext, d.notification_media_title)
            );

            showModalBs4Or5();
        }).fail(function(){
            alert('Failed to load history.');
        });
    });

    // SUBMIT (store/update)
    $('#historyForm').on('submit', function(e){
        e.preventDefault();

        const method = $('#history_form_method').val(); // POST or PUT
        const id = $('#history_id').val();

        let url = `{{ route('settings.employees.histories.store', ['employee' => $employee->uuid]) }}`;
        if(method === 'PUT'){
            url = `{{ route('settings.employees.histories.update', ['employee' => $employee->uuid, 'history' => '___ID___']) }}`
                .replace('___ID___', id);
        }

        const payload = $(this).serializeArray();
        if(method === 'PUT') payload.push({name:'_method', value:'PUT'});

        $.ajax({ url, type:'POST', data: payload })
            .done(function(res){
                if(!res.success) return;

                const h = res.history;
                const tillHtml = (h.till === 'Present') ? `<span class="badge bg-label-success rounded p-2">Present</span>` : h.till;

                const rowHtml = `
                    <tr id="hist-row-${h.id}">
                        <td>${h.designation}</td>
                        <td>${h.department}</td>
                        <td>${h.position_type}</td>
                        <td>${h.from}</td>
                        <td>${tillHtml}</td>
                        <td>
                            <button type="button" class="btn btn-sm btn-primary btnEditHistory" data-history-id="${h.id}">
                                <i class="bx bx-edit"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-danger btnDeleteHistory" data-history-id="${h.id}">
                                <i class="bx bx-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;

                const $existing = $(`#hist-row-${h.id}`);
                if($existing.length) $existing.replaceWith(rowHtml);
                else $('#employeeHistoryTbody').prepend(rowHtml);

                hideModalBs4Or5();
            })
            .fail(function(xhr){
                let html = '';
                if(xhr.responseJSON && xhr.responseJSON.errors){
                    Object.values(xhr.responseJSON.errors).forEach(arr => arr.forEach(msg => html += `<div>${msg}</div>`));
                } else {
                    html = '<div>Something went wrong.</div>';
                }
                $('#historyFormErrors').removeClass('d-none').html(html);
            });
    });

    // DELETE
    $(document).on('click', '.btnDeleteHistory', function(){
        const id = $(this).data('history-id');
        if(!confirm('Are you sure you want to delete this history record?')) return;

        const url = `{{ route('settings.employees.histories.destroy', ['employee' => $employee->uuid, 'history' => '___ID___']) }}`
            .replace('___ID___', id);

        $.ajax({
            url,
            type: 'POST',
            data: { _token: @json(csrf_token()), _method: 'DELETE' }
        }).done(function(res){
            if(res.success) $(`#hist-row-${id}`).remove();
        }).fail(function(){
            alert('Failed to delete history.');
        });
    });

});
</script>
@endpush
