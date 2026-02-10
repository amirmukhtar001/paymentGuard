@extends('layouts.' . config('settings.active_layout'))

@section('content')

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
                              action="{{ $item->exists ? route('settings.contacts.update', $item->uuid) : route('settings.contacts.store') }}">
                            @csrf
                            @if($item->exists)
                                @method('PUT')
                            @endif

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label req">Web Sites</label>
                                        <span class="help">
                                            @if($errors->has('company_id')) {!! $errors->first('company_id') !!} @endif
                                        </span>
                                        @include('components.company_field', [
                                        'companies' => $companies,
                                        'select_id' => 'company_id',
                                        'label' => 'Web Site',
                                        'selected' => $item->company_id ?? null
                                        ])
                                    </div>
                                </div>

                                <div class="col-md-4">
                                      <div class="form-group">
                                        <label class="form-label req">Department</label>
                                        <span class="help">
                                            @if($errors->has('department_id')) {!! $errors->first('department_id') !!} @endif
                                        </span>
                                        <select name="department_id" id="department_id"
                                                class="form-control select2"
                                                required>
                                            <option value="">Select Department</option>
                                            @foreach($departments as $id => $name)
                                                <option value="{{ $id }}"
                                                    {{ old('department_id', $item->department_id ?? '') == $id ? 'selected' : '' }}>
                                                    {{ $name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                  <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Name</label>
                                        <input type="text"
                                               name="name"
                                               class="form-control"
                                               value="{{ old('name', $item->name ?? '') }}">
                                    </div>
                                </div>
                            </div>
 

                            {{-- Contact info --}}
                            <div class="row">
                                    <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Designation</label>
                                         <span class="help">
                                            @if($errors->has('designation_id')) {!! $errors->first('designation_id') !!} @endif
                                        </span>
                                           <select name="designation_id" id="designation_id" class="form-control">
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

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Contact Number</label>
                                        <input type="text"
                                               name="contact_number"
                                               class="form-control"
                                               value="{{ old('contact_number', $item->contact_number ?? '') }}">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Ext No</label>
                                        <input type="text"
                                               name="ext_no"
                                               class="form-control"
                                               value="{{ old('ext_no', $item->ext_no ?? '') }}">
                                    </div>
                                </div>

                            </div>

                            {{-- Email & primary & status --}}
                            <div class="row">
                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Fax Number</label>
                                        <input type="text"
                                               name="fax_number"
                                               class="form-control"
                                               value="{{ old('fax_number', $item->fax_number ?? '') }}">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Email Address</label>
                                        <input type="email"
                                               name="email_address"
                                               class="form-control"
                                               value="{{ old('email_address', $item->email_address ?? '') }}">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Primary Contact</label>
                                        <div class="form-check mt-2">
                                            <input type="checkbox"
                                                   name="is_primary"
                                                   id="is_primary"
                                                   class="form-check-input"
                                                   value="1"
                                                   {{ old('is_primary', $item->is_primary ?? false) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_primary">
                                                Mark as primary contact
                                            </label>
                                        </div>
                                    </div>
                                </div>

                             
                            </div>

                            {{-- Address & remarks --}}
                            <div class="row">
                                   <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-label req">Status</label>
                                        @php
                                            $statusValue = old('status', $item->status ?? 'active');
                                        @endphp
                                        <select name="status" class="form-control" required>
                                            <option value="active" {{ $statusValue === 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="inactive" {{ $statusValue === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-label">Sort Order</label>
                                        <input type="number"
                                               name="sort_order"
                                               class="form-control"
                                               value="{{ old('sort_order', $item->sort_order ?? 0) }}">
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label class="form-label">Office Address</label>
                                        <textarea name="office_address"
                                                  class="form-control"
                                                  rows="3">{{ old('office_address', $item->office_address ?? '') }}</textarea>
                                    </div>
                                </div>

                            </div>

                            {{-- Sort --}}
                            <div class="row mb-4">

                            
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Remarks</label>
                                        <textarea name="remarks"
                                                  class="form-control"
                                                  rows="3">{{ old('remarks', $item->remarks ?? '') }}</textarea>
                                    </div>
                                </div>

                                
                            </div>

                            {{-- Buttons --}}
                            <div class="row">
                                <div class="col-12">
                                    <a href="{{ route('settings.contacts.list') }}" class="btn btn-warning">
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
