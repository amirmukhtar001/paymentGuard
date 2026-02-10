{{-- ====================== HISTORY MODAL (Add/Edit) ====================== --}}
<div class="modal fade" id="historyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="historyModalTitle">Add History</h5>

                {{-- compatible close for BS4 + BS5 --}}
                <button type="button" class="close btn-close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form id="historyForm">
                @csrf
                <input type="hidden" id="history_form_method" value="POST">
                <input type="hidden" id="history_id" value="">

                <div class="modal-body">
                    <div id="historyFormErrors" class="alert alert-danger d-none"></div>

                    <div class="row">
                        {{-- Cader --}}
                        <div class="col-md-4">
                            <label class="form-label">Cader</label>
                            <select name="cader" id="modal_cader" class="form-control select2">
                                <option value="">Select Cader</option>
                                @foreach($caderOptions as $opt)
                                <option value="{{ $opt->value }}">{{ $opt->value }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- BPS --}}
                        <div class="col-md-4">
                            <label class="form-label">BPS</label>
                            <select name="bps" id="modal_bps" class="form-control select2">
                                <option value="">Select BPS</option>
                                @foreach($bpsOptions as $opt)
                                <option value="{{ $opt->value }}">{{ $opt->value }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Designation --}}
                        <div class="col-md-4">
                            <label class="form-label req">Designation</label>
                            <span class="help">
                                @if($errors->has('designation_id')) {!! $errors->first('designation_id') !!} @endif
                            </span>
                            <select name="designation" id="designation_id" class="form-control select2">
                                <option value="">-- Select Designation --</option>

                                @foreach ($designations as $designation)
                                <option value="{{ $designation->id }}"
                                    {{ old('designation_id', $item->designation_id ?? '') == $designation->id ? 'selected' : '' }}>
                                    {{ $designation->title }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mt-2">
                        {{-- Department --}}
                        <div class="col-md-4">
                            <label class="form-label">Department</label>
                            <select name="department_id" id="modal_department_id" class="form-control select2">
                                <option value="">Select Department</option>
                                @foreach($departments as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <input type="hidden" name="position_type_id" id="modal_position_type_id" class="form-control" value="1"></input>
                        {{-- Position Type
                        <div class="col-md-4">
                            <label class="form-label req">Position Type</label>
                            <select name="position_type_id" id="modal_position_type_id" class="form-control select2" required>
                                <option value="">Select Position Type</option>
                                @foreach($positionTypes as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                        </select>
                    </div> --}}

                    {{-- Dates --}}
                    <div class="col-md-4">
                        <label class="form-label req">Working From</label>
                        <input type="date" name="working_from" id="modal_working_from" class="form-control" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Working Till</label>
                        <input type="date" name="working_till" id="modal_working_till" class="form-control">
                    </div>
                </div>

                <div class="row mt-2">
                    {{-- Notification Media --}}
                    <div class="col-md-12">
                        <label class="form-label">Notification (Order / File)</label>

                        <input type="hidden" name="notification_media_id" id="modal_notification_media_id" value="">

                        <button type="button"
                            class="btn btn-outline-primary open-media-manager"
                            data-mode="single"
                            data-target-input="#modal_notification_media_id"
                            data-preview-target="#modal_notification_media_preview">
                            Choose Notification Media
                        </button>

                        <div id="modal_notification_media_preview" class="mt-2">
                            <span class="text-muted">No media selected</span>
                        </div>
                    </div>
                </div>

        </div>

        <div class="modal-footer">
            <button type="button"
                class="btn btn-outline-secondary"
                data-dismiss="modal"
                data-bs-dismiss="modal">
                Close
            </button>
            <button type="submit" class="btn btn-success" id="historySubmitBtn">Save</button>
        </div>
        </form>

    </div>
</div>
</div>