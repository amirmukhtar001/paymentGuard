@extends('layouts.' . config('settings.active_layout'))

@section('content')

@include('components.media-manager', ['companies' => $companies])

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header header-elements-inline">
                <h5 class="card-title">{{ $title }}</h5>
                <div class="header-elements"></div>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-12">

                        <form method="POST"
                            action="{{ $item->exists ? route('settings.tenders.update', $item->uuid) : route('settings.tenders.store') }}">
                            @csrf
                            @if($item->exists)
                            @method('PUT')
                            @endif

                            <div class="row">
                                <div class="col-md-10"></div>

                                {{-- Main Tender Media Preview --}}
                                <div class="col-md-2">
                                    <div id="tender_media_preview" class="mt-2">
                                        @php
                                        $mediaUuid = old('tender_media_id', $item->media->uuid ?? null);
                                        @endphp

                                        @if($mediaUuid && $item->media)
                                        @php
                                        $ext = strtolower($item->media->extension ?? '');
                                        $url = $item->media->file_path
                                        ? asset('storage/' . $item->media->file_path)
                                        : null;
                                        $isImage = in_array($ext, ['jpg','jpeg','png','gif','webp','svg']);
                                        @endphp

                                        @if($url)
                                        @if($isImage)
                                        <div>
                                            <img src="{{ $url }}"
                                                alt="{{ $item->media->title }}"
                                                class="img-thumbnail"
                                                style="max-width: 50px; max-height: 50px; object-fit: cover;">
                                        </div>
                                        @else
                                        <div>
                                            <a href="{{ $url }}" target="_blank" rel="noopener">
                                                {{ $item->media->title ?? 'View file' }} ({{ strtoupper($ext) }})
                                            </a>
                                        </div>
                                        @endif
                                        @else
                                        <span class="text-muted">No media file path</span>
                                        @endif
                                        @else
                                        <span class="text-muted">No media selected</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- BASIC INFO --}}
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Tender Number</label>
                                        @error('tender_number')
                                        <span class="text-danger d-block">{{ $message }}</span>
                                        @enderror
                                        <input type="text"
                                            name="tender_number"
                                            class="form-control"
                                            value="{{ old('tender_number', $item->tender_number ?? '') }}">
                                    </div>
                                </div>

                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="form-label req">Title</label>
                                        @error('title')
                                        <span class="text-danger d-block">{{ $message }}</span>
                                        @enderror
                                        <input type="text"
                                            name="title"
                                            class="form-control"
                                            value="{{ old('title', $item->title ?? '') }}"
                                            required>
                                    </div>
                                </div>

                                {{-- Main Tender Media --}}
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Tender File ( Image / File)</label>
                                        {{-- hidden input to store selected media uuid --}}
                                        <input type="hidden" name="tender_media_id" id="tender_media_id" value="{{ old('tender_media_id', $item->media->uuid ?? '') }}">

                                        <button type="button" class="btn btn-outline-primary open-media-manager" data-mode="single"
                                            data-target-input="#tender_media_id" data-preview-target="#tender_media_preview"> Choose Tender File </button>
                                    </div>
                                </div>
                            </div>

                            {{-- STATUS / TYPE / COMPANY --}}
                            {{-- STATUS / TYPE / DEPARTMENT / COMPANY --}}
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label req">Status</label>
                                        @error('status')
                                        <span class="text-danger d-block">{{ $message }}</span>
                                        @enderror
                                        <select name="status" class="form-control select2" required>
                                            <option value="">Select Status</option>
                                            @foreach($statuses as $value => $label)
                                            @php
                                            $currentStatus = $item->status?->value ?? $item->status ?? '';
                                            @endphp
                                            <option value="{{ $value }}"
                                                {{ old('status', $currentStatus) == $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label req">Tender Type</label>
                                        @error('tender_type')
                                        <span class="text-danger d-block">{{ $message }}</span>
                                        @enderror
                                        <select name="tender_type" class="form-control select2" required>
                                            <option value="">Select Type</option>
                                            @foreach($tenderTypes as $value => $label)
                                            @php
                                            $currentType = $item->tender_type?->value ?? $item->tender_type ?? '';
                                            @endphp
                                            <option value="{{ $value }}"
                                                {{ old('tender_type', $currentType) == $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- DEPARTMENT --}}
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label req">Department</label>
                                        @error('department_id')
                                        <span class="text-danger d-block">{{ $message }}</span>
                                        @enderror
                                        <select name="department_id" id="department_id" class="form-control select2" required>
                                            <option value="">Select Department</option>
                                            @foreach($departments as $id => $name)
                                            <option value="{{ $id }}"
                                                {{ old('department_id', $item->department_id ?? null) == $id ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- Web Site / Company (dependent on Department) --}}
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label req">Web Site</label>
                                        <span class="help">
                                            @if($errors->has('company_id')) {!! $errors->first('company_id') !!} @endif
                                        </span>

                                        {{-- Keep your component – but we will repopulate its options via JS --}}
                                        @include('components.company_field', [
                                        'companies' => $companies,
                                        'select_id' => 'company_id',
                                        'label' => 'Web Site',
                                        'selected' => old('company_id', $item->company_id ?? null),
                                        ])
                                    </div>
                                </div>
                            </div>

                            {{-- DATES --}}
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Date of Advertisement</label>
                                        @error('date_of_advertisement')
                                        <span class="text-danger d-block">{{ $message }}</span>
                                        @enderror
                                        <input type="datetime-local"
                                            name="date_of_advertisement"
                                            class="form-control"
                                            value="{{ old(
           'date_of_advertisement',
           optional($item->date_of_advertisement)->format('Y-m-d\TH:i')
       ) }}">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Closing Date</label>
                                        @error('closing_date')
                                        <span class="text-danger d-block">{{ $message }}</span>
                                        @enderror
                                        <input type="datetime-local"
                                            name="closing_date"
                                            class="form-control"
                                            value="{{ old(
           'closing_date',
           optional($item->closing_date)->format('Y-m-d\TH:i')
       ) }}">

                                    </div>
                                </div>

                                {{-- Bidding Document Media --}}
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Bidding Document</label>

                                        <input type="hidden" name="bidding_document_media_id" id="bidding_document_media_id"
                                            value="{{ old('bidding_document_media_id', $item->biddingDocumentMedia->uuid ?? '') }}">

                                        <button type="button" class="btn btn-outline-primary open-media-manager" data-mode="single" data-target-input="#bidding_document_media_id"
                                            data-preview-target="#bidding_document_media_preview"> Choose Bidding Document </button>

                                        <div id="bidding_document_media_preview" class="mt-2">
                                            @php
                                            $bidMediaUuid = old('bidding_document_media_id', $item->biddingDocumentMedia->uuid ?? null);
                                            $bidMedia = $item->biddingDocumentMedia ?? null;
                                            @endphp

                                            @if($bidMediaUuid && $bidMedia)
                                            @php
                                            $ext = strtolower($bidMedia->extension ?? '');
                                            $url = $bidMedia->file_path
                                            ? asset('storage/' . $bidMedia->file_path)
                                            : null;
                                            $isImage = in_array($ext, ['jpg','jpeg','png','gif','webp','svg']);
                                            @endphp

                                            @if($url)
                                            @if($isImage)
                                            <div>
                                                <img src="{{ $url }}" alt="{{ $bidMedia->title }}" class="img-thumbnail" style="max-width: 150px; max-height: 150px; object-fit: cover;">
                                                <div class="small text-muted mt-1">
                                                    ID: {{ $bidMedia->title }}
                                                </div>
                                            </div>
                                            @else
                                            <div>
                                                <a href="{{ $url }}" target="_blank" rel="noopener">
                                                    {{ $bidMedia->title ?? 'View file' }} ({{ strtoupper($ext) }})
                                                </a>
                                                <div class="small text-muted mt-1">

                                                </div>
                                            </div>
                                            @endif
                                            @else
                                            <span class="text-muted">No media file path</span>
                                            @endif
                                            @else
                                            <span class="text-muted">No bidding document selected</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- DESCRIPTION --}}
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Description</label>
                                        @error('description')
                                        <span class="text-danger d-block">{{ $message }}</span>
                                        @enderror
                                        <textarea name="description"
                                            class="form-control"
                                            rows="4"
                                            placeholder="Tender description / details">{{ old('description', $item->description ?? '') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            {{-- BUTTONS --}}
                            <div class="row mt-3">
                                <div class="col-12">
                                    <a href="{{ route('settings.tenders.list') }}" class="btn btn-warning">
                                        <i class="bx bx-arrow-back tf-icons"></i> Back
                                    </a>

                                    <button type="submit" class="btn btn-success">
                                        <i class="bx bx-save"></i> Save
                                    </button>
                                </div>
                            </div>

                        </form>

                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#tender_media_id').on('change', function() {
            $('#tender_media_id_label').text($(this).val() || '-');
        });

        $('#bidding_document_media_id').on('change', function() {
            $('#bidding_document_media_id_label').text($(this).val() || '-');
        });
        // =========================
        // DEPARTMENT → COMPANIES
        // =========================
        function loadCompaniesByDepartment(departmentId, selectedCompanyId = null) {
            var $companySelect = $('#company_id');
            // Clear existing options
            $companySelect.empty();
            $companySelect.append('<option value="">Select Web Site</option>');
            if (!departmentId) {
                // No department selected; nothing more to do
                $companySelect.trigger('change');
                return;
            }
            $.ajax({
                url: "{{ route('settings.departments.companies', ['department' => 'DEPT_ID']) }}".replace('DEPT_ID', departmentId),
                type: 'GET',
                success: function(companies) {
                    companies.forEach(function(company) {
                        var option = new Option(company.title, company.id, false, false);
                        if (selectedCompanyId && parseInt(selectedCompanyId) === parseInt(company.id)) {
                            option.selected = true;
                        }
                        $companySelect.append(option);
                    });
                    $companySelect.trigger('change'); // for select2
                },
                error: function() {
                    // You can show some error if you like
                    console.error('Failed to load companies for department ' + departmentId);
                }
            });
        }

        // On department change
        $('#department_id').on('change', function() {
            var departmentId = $(this).val();
            //  loadCompaniesByDepartment(departmentId, null);
        });

        // On page load (for edit form): if department & company are set, load and preselect
        var initialDepartmentId = $('#department_id').val();
        var initialCompanyId = "{{ old('company_id', $item->company_id ?? '') }}";

        if (initialDepartmentId) {
            //loadCompaniesByDepartment(initialDepartmentId, initialCompanyId);
        }
    });
</script>
@endpush