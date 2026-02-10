<div class="modal fade" id="positionModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="positionModalTitle">Add Position</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="positionForm">
                @csrf
                <input type="hidden" id="position_form_method" value="POST">
                <input type="hidden" id="position_id" value="">

                <div class="modal-body">
                    <div id="positionFormErrors" class="alert alert-danger d-none"></div>

                    <div class="row">
                        <div class="col-md-3">
                            <label class="form-label req">Position Type</label>
                            <select id="modal_position_type_id" name="position_type_id" class="form-control select2" required>
                                <option value="">Select Position Type</option>
                                @foreach($positionTypes as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Department</label>
                            <select id="modal_department_id" name="department_id" class="form-control select2">
                                <option value="">Select Department</option>
                                @foreach($departments as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Party</label>
                            <select id="modal_party_id" name="party_id" class="form-control select2">
                                <option value="">Select Party</option>
                                @foreach($parties as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Halqa</label>
                            <select id="modal_halqa_id" name="halqa_id" class="form-control select2">
                                <option value="">Select Halqa</option>
                                @foreach($halqas as $id => $code)
                                <option value="{{ $id }}">{{ $code }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-3">
                            <label class="form-label req">Working From</label>
                            <input type="date" id="modal_working_from_date" name="working_from_date" class="form-control" required>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Working Till</label>
                            <input type="date" id="modal_working_till_date" name="working_till_date" class="form-control">
                            <small class="text-muted">Leave empty if still in office</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Position Media (Notification / Order)</label>

                            <input type="hidden"
                                name="position_media_id"
                                id="modal_position_media_id"
                                value="">

                            <button type="button"
                                class="btn btn-outline-primary open-media-manager" 
                                data-mode="single"
                                data-target-input="#modal_position_media_id"
                                data-preview-target="#modal_position_media_preview">
                                Choose Position Media
                            </button>

                            <div id="modal_position_media_preview" class="mt-2">
                                <span class="text-muted">No media selected</span>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                 <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success" id="positionSubmitBtn">Save</button>
                </div>
            </form>

        </div>
    </div>
</div>