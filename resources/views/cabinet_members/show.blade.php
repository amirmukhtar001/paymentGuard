{{-- resources/views/cabinet_members/show.blade.php --}}
@extends('layouts.' . config('settings.active_layout'))

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header header-elements-inline d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">{{ $title ?? 'Detailed Profile' }}</h5>

                <div class="header-elements">
                    <a href="{{ route('settings.cabinetmembers.list') }}" class="btn btn-warning  btn-sm">
                        <i class="bx bx-arrow-back tf-icons"></i> Back
                    </a>

                    <a href="{{ route('settings.cabinetmembers.edit', $cabinetMember->uuid) }}" class="btn btn-primary ml-2 btn-sm">
                        <i class="bx bx-edit"></i> Edit
                    </a>
                </div>
            </div>

            <div class="card-body">
                @php
                $memberMedia = $cabinetMember->media ?? null;
                $memberMediaExt = strtolower($memberMedia->extension ?? '');
                $memberMediaUrl = $memberMedia && $memberMedia->file_path ? asset('storage/' . $memberMedia->file_path) : null;
                $memberIsImage = in_array($memberMediaExt, ['jpg','jpeg','png','gif','webp','svg']);

                // If you have a "currentPosition" relation, use it. Otherwise, pick the latest from positions
                $current = $cabinetMember->currentPosition ?? $cabinetMember->positions?->sortByDesc('working_from_date')->first();

                $positionMedia = $current->media ?? null;
                $positionMediaExt = strtolower($positionMedia->extension ?? '');
                $positionMediaUrl = $positionMedia && $positionMedia->file_path ? asset('storage/' . $positionMedia->file_path) : null;
                $positionIsImage = in_array($positionMediaExt, ['jpg','jpeg','png','gif','webp','svg']);
                @endphp

                {{-- TOP SUMMARY --}}
                <div class="row align-items-start">
                    <div class="col-md-9">
                        <h4 class="mb-2">{{ $cabinetMember->name }}</h4>

                        <div class="mb-2">
                            <span class="badge badge-info">{{ $cabinetMember->member_type ?? '-' }}</span>
                            <span class="badge badge-{{ ($cabinetMember->status ?? 'Active') === 'Active' ? 'success' : 'secondary' }}">
                                {{ $cabinetMember->status ?? '-' }}
                            </span>

                            @if(($cabinetMember->make_leader ?? 'no') === 'yes')
                            <span class="badge badge-warning">Leader (Home Page)</span>
                            @endif
                        </div>

                        @if($current)
                        <div class="text-muted">
                            <strong>Current Position:</strong>
                            {{ $current->positionType->name ?? '-' }}
                            @if(!empty($current->department?->name))
                            — {{ $current->department->name }}
                            @endif
                        </div>
                        @endif
                    </div>

                    <div class="col-md-3 text-md-right">
                        @if($memberMediaUrl)
                        @if($memberIsImage)
                        <img src="{{ $memberMediaUrl }}"
                            alt="{{ $memberMedia->title ?? $cabinetMember->name }}"
                            class="img-thumbnail"
                            style="max-width: 160px; max-height: 160px; object-fit: cover;">
                        @else
                        <a href="{{ $memberMediaUrl }}" target="_blank" rel="noopener">
                            {{ $memberMedia->title ?? 'View Profile Media' }} ({{ strtoupper($memberMediaExt) }})
                        </a>
                        @endif
                        @else
                        <div class="text-muted">No Profile Image</div>
                        @endif
                    </div>
                </div>

                <hr>

                {{-- BASIC + CONTACT INFO --}}
                <div class="row">
                    <div class="col-md-6">
                        <h5>Basic Information</h5>

                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <tbody>
                                    <tr>
                                        <th style="width: 35%;">Name</th>
                                        <td>{{ $cabinetMember->name ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Date of Birth</th>
                                        <td>
                                            @if($cabinetMember->dob)
                                            {{ \Illuminate\Support\Carbon::parse($cabinetMember->dob)->format('d M Y') }}
                                            @else
                                            -
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Sort Order</th>
                                        <td>{{ $cabinetMember->sort_order ?? 0 }}</td>
                                    </tr>
                                    <tr>
                                        <th>Website</th>
                                        <td>
                                            {{-- If your model has company relation, show it; otherwise show company_id --}}
                                            {{ $cabinetMember->company->title ?? $cabinetMember->company_id ?? '-' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Leader</th>
                                        <td>{{ ($cabinetMember->make_leader ?? 'no') === 'yes' ? 'Yes' : 'No' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h5>Contact Information</h5>

                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <tbody>
                                    <tr>
                                        <th style="width: 35%;">Contact No</th>
                                        <td>{{ $cabinetMember->contact_no ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Office No</th>
                                        <td>{{ $cabinetMember->office_no ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Email</th>
                                        <td>
                                            @if(!empty($cabinetMember->email))
                                            <a href="mailto:{{ $cabinetMember->email }}">{{ $cabinetMember->email }}</a>
                                            @else
                                            -
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Facebook</th>
                                        <td>
                                            @if(!empty($cabinetMember->facebook_page))
                                            <a href="{{ $cabinetMember->facebook_page }}" target="_blank" rel="noopener">
                                                {{ $cabinetMember->facebook_page }}
                                            </a>
                                            @else
                                            -
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Twitter / X</th>
                                        <td>
                                            @if(!empty($cabinetMember->twitter_page))
                                            <a href="{{ $cabinetMember->twitter_page }}" target="_blank" rel="noopener">
                                                {{ $cabinetMember->twitter_page }}
                                            </a>
                                            @else
                                            -
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <hr>

                {{-- CURRENT POSITION --}}
                <div class="row">
                    <div class="col-12">
                        <h5>Current Position</h5>

                        @if($current)
                        <div class="row">
                            <div class="col-md-8">
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered">
                                        <tbody>
                                            <tr>
                                                <th style="width: 30%;">Position Type</th>
                                                <td>{{ $current->positionType->name ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Department</th>
                                                <td>{{ $current->department->name ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Party</th>
                                                <td>{{ $current->party->short_name ?? $current->party->name ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Halqa</th>
                                                <td>{{ $current->halqa->code ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Working From</th>
                                                <td>
                                                    @if($current->working_from_date)
                                                    {{ \Illuminate\Support\Carbon::parse($current->working_from_date)->format('d M Y') }}
                                                    @else
                                                    -
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Working Till</th>
                                                <td>
                                                    @if($current->working_till_date)
                                                    {{ \Illuminate\Support\Carbon::parse($current->working_till_date)->format('d M Y') }}
                                                    @else
                                                    <span class="badge bg-label-success rounded p-2">Present</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-2"><strong>Notification / Order</strong></div>

                                @if($positionMediaUrl)
                                @if($positionIsImage)
                                <img src="{{ $positionMediaUrl }}"
                                    alt="{{ $positionMedia->title ?? 'Notification / Order' }}"
                                    class="img-thumbnail"
                                    style="max-width: 100%; max-height: 220px; object-fit: cover;">
                                @else
                                <a href="{{ $positionMediaUrl }}" target="_blank" rel="noopener">
                                    {{ $positionMedia->title ?? 'View file' }} ({{ strtoupper($positionMediaExt) }})
                                </a>
                                @endif
                                @else
                                <div class="text-muted">NoNotification / Order</div>
                                @endif
                            </div>
                        </div>
                        @else
                        <div class="alert alert-warning mb-0">
                            No position record found for this cabinet member.
                        </div>
                        @endif
                    </div>
                </div>

                <hr>

                {{-- MESSAGE / QUOTE --}}
                <div class="row">
                    <div class="col-12">
                        <h5>Message / Quote</h5>

                        @if(!empty($cabinetMember->message))
                        <div class="border rounded p-3">
                            {!! $cabinetMember->message !!}
                        </div>
                        @else
                        <div class="text-muted">No message added.</div>
                        @endif
                    </div>
                </div>

                {{-- OPTIONAL: ALL POSITIONS --}}
                @if($cabinetMember->positions && $cabinetMember->positions->count())
                <hr>
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="mb-0">All Positions</h5>

                            <button type="button"
                                class="btn btn-success"
                                id="btnAddPosition"
                                data-member-uuid="{{ $cabinetMember->uuid }}">
                                <i class="bx bx-plus"></i> Add Another Position
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-sm table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Position</th>
                                        <th>Department</th> 
                                        <th>From</th>
                                        <th>Till</th>
                                        <th>Notification / Order</th>
                                        <th style="width: 140px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="allPositionsTbody">
                                    @foreach($cabinetMember->positions->sortByDesc('working_from_date') as $pos)
                                    <tr id="pos-row-{{ $pos->id }}">
                                        <td>{{ $pos->positionType->name ?? '-' }}</td>
                                        <td>{{ $pos->department->name ?? '-' }}</td>
                                        <!-- <td>{{ $pos->party->short_name ?? $pos->party->name ?? '-' }}</td>
                                        <td>{{ $pos->halqa->code ?? '-' }}</td> -->
                                        <td>{{ $pos->working_from_date ? \Illuminate\Support\Carbon::parse($pos->working_from_date)->format('d M Y') : '-' }}</td>
                                        <td>
                                            @if($pos->working_till_date)
                                            {{ \Illuminate\Support\Carbon::parse($pos->working_till_date)->format('d M Y') }}
                                            @else
                                            <span class="badge bg-label-success rounded p-2">Present</span>
                                            @endif
                                        </td>
                                        <td>
                                        @php 
                                            $positionMedia = $pos->media ?? null;
                                            $positionMediaExt = strtolower($positionMedia->extension ?? '');
                                            $positionMediaUrl = $positionMedia && $positionMedia->file_path ? asset('storage/' . $positionMedia->file_path) : null;
                                            $positionIsImage = in_array($positionMediaExt, ['jpg','jpeg','png','gif','webp','svg']);
                                        @endphp
                                @if($positionMediaUrl)
                                @if($positionIsImage)
                                <img src="{{ $positionMediaUrl }}"
                                    alt="{{ $positionMedia->title ?? 'Notification / Order' }}"
                                    class="img-thumbnail"
                                    style="max-width: 100%; max-height: 220px; object-fit: cover;">
                                @else
                                <a href="{{ $positionMediaUrl }}" target="_blank" rel="noopener">
                                    {{ $positionMedia->title ?? 'View file' }} ({{ strtoupper($positionMediaExt) }})
                                </a>
                                @endif
                                @else
                                <div class="text-muted">NoNotification / Order</div>
                                @endif
                                        </td>
                                        <td>
                                            <button type="button"
                                                class="btn btn-sm btn-primary btnEditPosition"
                                                data-position-id="{{ $pos->id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>

                                            <button type="button"
                                                class="btn btn-sm btn-danger btnDeletePosition"
                                                data-position-id="{{ $pos->id }}">
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
                @endif

            </div>
        </div>
    </div>
</div>
@include('cabinet_members.partials.position-modal')
@include('components.media-manager', ['companies' => $companies])



@endsection


@push('scripts')
<script>
    $(document).ready(function() {
        const memberUuid = @json($cabinetMember->uuid);

        // If your select2 needs init inside modal:
        $('#positionModal').on('shown.bs.modal', function() {
            $(this).find('.select2').select2({
                dropdownParent: $('#positionModal')
            });
        });

        function resetPositionModal() {
            $('#positionModalTitle').text('Add Position');
            $('#positionSubmitBtn').text('Save');
            $('#position_form_method').val('POST');
            $('#position_id').val('');

            $('#positionFormErrors').addClass('d-none').html('');

            $('#modal_position_type_id').val('').trigger('change');
            $('#modal_department_id').val('').trigger('change');
            $('#modal_party_id').val('').trigger('change');
            $('#modal_halqa_id').val('').trigger('change');

            $('#modal_working_from_date').val('');
            $('#modal_working_till_date').val('');

            $('#modal_position_media_id').val('');
            $('#modal_position_media_preview').html('<span class="text-muted">No media selected</span>');
        }

        function renderMediaPreview(mediaUrl, mediaExt, mediaTitle) {
            if (!mediaUrl) {
                return '<span class="text-muted">No media selected</span>';
            }
            const isImage = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'].includes((mediaExt || '').toLowerCase());
            if (isImage) {
                return `
                <div>
                    <img src="${mediaUrl}" class="img-thumbnail" style="max-width: 150px; max-height: 150px; object-fit: cover;">
                    <div class="small text-muted mt-1">${mediaTitle || ''}</div>
                </div>
            `;
            }
            return `<a href="${mediaUrl}" target="_blank" rel="noopener">${mediaTitle || 'View file'} (${(mediaExt||'').toUpperCase()})</a>`;
        }

        // ADD
        $('#btnAddPosition').on('click', function() {
            resetPositionModal();
            $('#positionModal').modal('show');
        });

        // EDIT: load JSON then fill modal
        $(document).on('click', '.btnEditPosition', function() {
            const posId = $(this).data('position-id');
            resetPositionModal();

            $('#positionModalTitle').text('Edit Position');
            $('#positionSubmitBtn').text('Update');
            $('#position_form_method').val('PUT');
            $('#position_id').val(posId);

            $.get(`{{ route('settings.cabinetmembers.positions.show', ['cabinetMember' => $cabinetMember->uuid, 'position' => '___ID___']) }}`.replace('___ID___', posId))
                .done(function(res) {
                    if (!res.success) return;

                    const d = res.data;
                    $('#modal_position_type_id').val(d.position_type_id).trigger('change');
                    $('#modal_department_id').val(d.department_id || '').trigger('change');
                    $('#modal_party_id').val(d.party_id || '').trigger('change');
                    $('#modal_halqa_id').val(d.halqa_id || '').trigger('change');

                    $('#modal_working_from_date').val(d.working_from_date || '');
                    $('#modal_working_till_date').val(d.working_till_date || '');

                    $('#modal_position_media_id').val(d.position_media_uuid || '');
                    $('#modal_position_media_preview').html(
                        renderMediaPreview(d.position_media_url, d.position_media_ext, d.position_media_title)
                    );

                    $('#positionModal').modal('show');
                })
                .fail(function() {
                    alert('Failed to load position.');
                });
        });

        // SUBMIT (store/update)
        $('#positionForm').on('submit', function(e) {
            e.preventDefault();

            const method = $('#position_form_method').val(); // POST or PUT
            const posId = $('#position_id').val();

            let url = `{{ route('settings.cabinetmembers.positions.store', ['cabinetMember' => $cabinetMember->uuid]) }}`;

            if (method === 'PUT') {
                url = `{{ route('settings.cabinetmembers.positions.update', ['cabinetMember' => $cabinetMember->uuid, 'position' => '___ID___']) }}`.replace('___ID___', posId);
            }

            const payload = $(this).serializeArray();

            // For PUT via AJAX: spoof method
            if (method === 'PUT') {
                payload.push({
                    name: '_method',
                    value: 'PUT'
                });
            }

            $.ajax({
                    url: url,
                    type: 'POST',
                    data: payload,
                })
                .done(function(res) {
                    if (!res.success) return;

                    // Update table row (or add new)
                    const p = res.position;

                    const tillHtml = (p.till === 'Present') ?
                        `<span class="badge bg-label-success rounded p-2">Present</span>` :
                        p.till;

                    const rowHtml = `
                <tr id="pos-row-${p.id}">
                    <td>${p.position_type}</td>
                    <td>${p.department}</td>
                    <td>${p.party}</td>
                    <td>${p.halqa}</td>
                    <td>${p.from}</td>
                    <td>${tillHtml}</td>
                    <td>
                        <button type="button" class="btn btn-sm btn-primary btnEditPosition" data-position-id="${p.id}">
                            <i class="bx bx-edit"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-danger btnDeletePosition" data-position-id="${p.id}">
                            <i class="bx bx-trash"></i>
                        </button>
                    </td>
                </tr>
            `;

                    // if exists replace else prepend
                    const $existing = $(`#pos-row-${p.id}`);
                    if ($existing.length) {
                        $existing.replaceWith(rowHtml);
                    } else {
                        // put at top of tbody
                        $('#allPositionsTbody').prepend(rowHtml);
                    }

                    $('#positionModal').modal('hide');
                })
                .fail(function(xhr) {
                    let html = '';
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        Object.values(xhr.responseJSON.errors).forEach(arr => {
                            arr.forEach(msg => html += `<div>${msg}</div>`);
                        });
                    } else {
                        html = '<div>Something went wrong.</div>';
                    }
                    $('#positionFormErrors').removeClass('d-none').html(html);
                });
        });

        // DELETE
        $(document).on('click', '.btnDeletePosition', function() {
            const posId = $(this).data('position-id');

            if (!confirm('Are you sure you want to delete this position?')) return;

            const url = `{{ route('settings.cabinetmembers.positions.destroy', ['cabinetMember' => $cabinetMember->uuid, 'position' => '___ID___']) }}`.replace('___ID___', posId);

            $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: @json(csrf_token()),
                        _method: 'DELETE'
                    }
                })
                .done(function(res) {
                    if (res.success) {
                        $(`#pos-row-${posId}`).remove();
                    }
                })
                .fail(function() {
                    alert('Failed to delete position.');
                });
        });
    });
</script>
@endpush