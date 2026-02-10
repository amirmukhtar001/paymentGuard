@extends('layouts.'.config('settings.active_layout'))

@push('vendor-style')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
@endpush

@push('scripts')
<script src="{{asset('assets/vendor/libs/bootstrap-maxlength/bootstrap-maxlength.js')}}"></script>
<script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
<script src="{{ asset('assets/js/Settings/drop-down.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

<!-- <script src="{{ asset('assets/js/app-custom.js') }}"></script> -->
<script type="text/javascript">
    function check_hod() {
        var company_id = $('#company_id').val();

        var data = {
            company_id: company_id,
            _token: '{{ csrf_token() }}',
        };

        var user_id = "{{$item? $item->id : null}}";
        if (user_id !== null) {
            data.user_id = user_id;
        }
        $.ajax({
            type: 'post',
            url: "{{ route('noauth.users-hod-check') }}",
            data: data,
            success: function(res) {
                if (res == 1) {
                    alert('Head of Department user already Exist.');
                    $('#is_hod_no').prop('checked', true);

                }
            }
        })

    }
    $(document).ready(function() {

        $('.mobile').mask('0000-0000000');
        $('.cnic').mask('00000-0000000-0');

        $('#username').on('input', function () {
    const usernameVal = $(this).val();
    if (usernameVal.length < 6) {
        $('#username_error').text('Username must be at least 6 characters long');
        $(this).addClass('is-invalid');
    } else {
        $('#username_error').text('');
        $(this).removeClass('is-invalid');
    }
});

        $("#company_id").change(function() {
            var company_id = $(this).val()
            $('#is_hod_no').prop('checked', true);

            var data = {
                id: company_id,
                _token: '{{ csrf_token() }}',
            };

            var user_id = "{{$item? $item->id : null}}";
            if (user_id !== null) {
                data.user_id = user_id;
            }
            var currentSection = $('#section_id').val();
            if (currentSection) {
                data.section_id = currentSection;
            }

            $.ajax({
                type: 'post',
                url: "{{ route('settings.companies.details') }}",
                data: data,
                success: function(res) {

                    // setting section
                    var sections = "<option value>Select {{ config('settings.section_title') }}</option>"
                    $.each(res?.sections || [], function(i, v) {
                        sections += "<option value='" + i + "'>" + v + "</option>"
                    })
                    $("#section_id").html(sections)

                    //setting User of Company
                    var users = "<option value>This is parent user</option>"
                    $.each(res?.users || [], function(i, v) {
                        users += "<option value='" + i + "'>" + v + "</option>"
                    })
                    $("#parent_id").html(users);
                    //setting district and tehsil
                    // Set selected option for district_id
                    // Add and select option for district_id
                    // $('#district_id').append(new Option(res?.district?.title, res?.district?.id, true, true)).trigger('change');

                    // Add and select option for tehsil_id
                    // $('#tehsil_id').append(new Option(res?.tehsil?.title, res?.tehsil?.id, true, true)).trigger('change');
                }
            })

        })

    })
</script>
@endpush

@section('content')

<div class="row">
    <div class="col-12">

        <!-- Traffic sources -->
        <div class="card">
            <div class="card-header header-elements-inline">
                <h5 class="card-title">{{ $title }}</h5>
                <div class="header-elements">
                    <div class="form-check form-check-right form-check-switchery form-check-switchery-sm">

                        {{--<label class="form-check-label">
                                Live update:
                                <input type="checkbox" class="form-input-switchery" checked data-fouc>
                            </label>--}}
                    </div>
                </div>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-12">

                        <form enctype="multipart/form-data" method="POST" action="{{ $item->exists ? route('settings.users-mgt.update', \Illuminate\Support\Facades\Crypt::encrypt($item->id)) : route('settings.users-mgt.store') }}" id="userForm">
                            @csrf
                            @if($item->exists)
                                @method('PUT')
                            @endif

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="name" class="form-label req">Title / Name </label>
                                    <span class="help">@if(session()->has('errors')) {!! session()->get('errors')->first('name') !!}@endif</span>
                                    <input type="text" name="name" value="{{ old('name', $item->name ?? null) }}" class="form-control" id="name" required>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label for="designation" class="form-label">Designation</label>
                                    <span class="help">@if(session()->has('errors')) {!! session()->get('errors')->first('designation') !!}@endif</span>
                                    <input type="text" name="designation" value="{{ old('designation', $item->designation ?? null) }}" class="form-control" id="designation">
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="email" class="form-label req">Email Address </label>
                                    <span class="help">@if(session()->has('errors')) {!! session()->get('errors')->first('email') !!}@endif</span>
                                    <input type="email" name="email" value="{{ old('email', $item->email ?? null) }}" class="form-control" id="email" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="username" class="form-label req">Username </label>
                                    <span class="help">@if(session()->has('errors')) {!! session()->get('errors')->first('username') !!}@endif</span>
                                    <input type="text" name="username" value="{{ old('username', $item->username ?? null) }}" class="form-control" id="username" required>
                                    <div id="username_error" class="text-danger"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="cnic" class="form-label">CNIC No</label>
                                    <span class="help">@if(session()->has('errors')) {!! session()->get('errors')->first('cnic') !!}@endif</span>
                                    <input type="text" name="cnic" value="{{ old('cnic', $item->cnic ?? null) }}" class="form-control maxlength-number-input cnic" id="cnic" maxlength="13">
                                </div>
                            </div>

                        </div>

                        <div class="row">



                        <div class="col-6">
                                    <div class="form-group">
                                        <label for="password" class="form-label req">Password</label>

                                        {{-- Hint on edit --}}
                                        @if($item->exists)
                                        <span class="help">
                                            Leave empty if you don't want to change password.
                                        </span>
                                        @endif

                                        {{-- Validation error --}}
                                        @error('password')
                                        <span class="help text-danger">{{ $message }}</span>
                                        @enderror

                                        @php
                                        // Build attributes: always include class & id, add required on create
                                        $passwordAttrs = ['class' => 'form-control', 'id' => 'password'];
                                        if (! $item->exists) {
                                            $passwordAttrs['required'] = 'required';
                                        }
                                        @endphp

                                        <div class="input-group">
                                        {{-- Password input --}}
                                        <input type="password" name="password" @foreach($passwordAttrs as $attr => $val) {{ $attr }}="{{ $val }}" @endforeach>
                                        {{-- Eye icon toggle --}}
                                        <span class="input-group-text" id="toggle-password" style="cursor: pointer;">
                                            <i class="fas fa-eye" aria-hidden="true"></i>
                                        </span>
                                        </div>

                                        <small id="password-strength"></small>
                                    </div>
                                    </div>




                                    <div class="col-6">
                            <div class="form-group">
                                <label for="password_confirmation" class="form-label req">Confirm New Password</label>

                                {{-- Validation error --}}
                                @error('password_confirmation')
                                <span class="help text-danger">{{ $message }}</span>
                                @enderror

                                @php
                                // Build attributes: always include class & id, add required on create
                                $confirmAttrs = ['class' => 'form-control', 'id' => 'password_confirmation', 'name' => 'password_confirmation'];
                                if (! $item->exists) {
                                    $confirmAttrs['required'] = 'required';
                                }
                                @endphp

                                <div class="input-group">
                                {{-- Password confirmation input --}}
                                <input type="password"
                                        @foreach($confirmAttrs as $attr => $val) {{ $attr }}="{{ $val }}" @endforeach>

                                {{-- Eye icon toggle --}}
                                <span class="input-group-text" id="toggle-password-confirmation" style="cursor: pointer;">
                                    <i class="fas fa-eye"></i>
                                </span>
                                </div>

                                <small id="match-message"></small>
                            </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="contact_number" class="form-label">Contact No</label>
                                    <span class="help">@if(session()->has('errors')) {!! session()->get('errors')->first('contact_number') !!}@endif</span>
                                    <input type="text" name="contact_number" value="{{ old('contact_number', $item->contact_number ?? null) }}" class="form-control mobile" id="contact_number">
                                </div>
                            </div>


                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="company_id" class="control-label req">Select {{ config('settings.company_title') }}</label>

                                    <span class="help">@if(Session::has('errors')) {!! Session::get('errors')->first('company_id') !!} @endif</span>
                                    <!-- <select class="dynamic-select" data-placeHolder="Select Organization" data-route="{{ route('noauth.companies-list') }}"  name="company_id" id="company_id" >
                                </select> -->
                                    <select name="company_id" class="form-select select2" id="company_id">
                                        <option value="0">Select Web Site {{ config('settings.company_title') }}</option>
                                        @foreach($companies_dd as $id => $title)
                                            <option value="{{ $id }}" {{ old('company_id', $item->company_id ?? null) == $id ? 'selected' : '' }}>{{ $title }}</option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>

                            <!-- <div class="col-6">
                                <div class="form-group">
                                    <label for="section_id" class="control-label">Select {{ config('settings.section_title') }}</label>

                                    <span class="help">@if(Session::has('errors')) {!! Session::get('errors')->first('section_id') !!} @endif</span>
                                    <select name="section_id" class="form-select select2" id="section_id">
                                        <option value>Select {{ config('settings.section_title') }}</option>
                                        @foreach($sections as $id => $title)
                                            <option value="{{ $id }}" {{ old('section_id', $item->section_id ?? null) == $id ? 'selected' : '' }}>{{ $title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div> -->
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="parent_id" class="control-label">Select Parent User </label>

                                    <span class="help">@if(Session::has('errors')) {!! Session::get('errors')->first('parent_id') !!} @endif</span>
                                    <select name="parent_id" class="form-select select2" id="parent_id">
                                        <option value>This is a Parent User</option>
                                        @foreach($parent_users as $id => $name)
                                            <option value="{{ $id }}" {{ old('parent_id', $item->parent_id ?? null) == $id ? 'selected' : '' }}>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label for="role_id[]" class="control-label">Select Roles </label>

                                    <span class="help">@if(Session::has('errors')) {!! Session::get('errors')->first('role_id[]') !!} @endif</span>
                                    <select name="role_id[]" class="form-select select2" id="role_id[]" multiple>
                                        @php
                                            $selectedRoles = old('role_id', ($item->exists && $item->roles->count() > 0) ? $item->roles->pluck('id')->toArray() : []);
                                        @endphp
                                        @foreach($roles as $id => $name)
                                            <option value="{{ $id }}" {{ in_array($id, (array)$selectedRoles) ? 'selected' : '' }}>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="status" class="control-label req">Status</label>
                                    <span class="help">@if(Session::has('errors')) {!! Session::get('errors')->first('status') !!} @endif</span>
                                    <select name="status" class="form-select" id="status">
                                        <option value="1" {{ old('status', $item->status ?? 1) == 1 ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ old('status', $item->status ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    {!! Form::label('district_ids', 'Select Districts', ['class' => 'control-label']) !!}
                                    <div class="mb-2">
                                        <button type="button" class="btn btn-sm btn-outline-primary" id="selectAllDistricts">
                                            <i class="bx bx-check-square"></i> Select All Districts
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary" id="clearAllDistricts">
                                            <i class="bx bx-x"></i> Clear All
                                        </button>
                                        <small class="text-muted d-block">Districts will be loaded in batches for better performance</small>
                                    </div>
                                    <span class="help">@if(Session::has('errors')) {!! Session::get('errors')->first('district_ids') !!} @endif</span>
                                    @php
                                        $districtOptions = ($oldDistricts ?? collect())->toArray();
                                        if($item && $item->districts){
                                            $districtOptions += $item->districts->pluck('title','id')->toArray();
                                        }
                                        $districtSelected = old('district_ids', $item && $item->districts ? $item->districts->pluck('id')->toArray() : []);
                                    @endphp
                                    {!! Form::select('district_ids[]', $districtOptions, $districtSelected, ['class' => 'dynamic-select', 'id' => 'district_ids', 'multiple' => 'multiple', 'data-route' => route('dynamic.dropDown'), 'data-statment' => \Illuminate\Support\Facades\Crypt::encrypt(['model' => 'districts', 'connection' => 'mysql', 'label' => 'title', 'value' => 'id'])]) !!}
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    {!! Form::label('tehsil_ids', 'Select Tehsils', ['class' => 'control-label']) !!}
                                    <div class="mb-2">
                                        <button type="button" class="btn btn-sm btn-outline-primary" id="selectAllTehsils">
                                            <i class="bx bx-check-square"></i> Select All Tehsils
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary" id="clearAllTehsils">
                                            <i class="bx bx-x"></i> Clear All
                                        </button>
                                        <small class="text-muted d-block">Requires districts to be selected first</small>
                                    </div>
                                    <span class="help">@if(Session::has('errors')) {!! Session::get('errors')->first('tehsil_ids') !!} @endif</span>
                                    @php
                                        $tehsilOptions = ($oldTehsils ?? collect())->toArray();
                                        if($item && $item->tehsils){
                                            $tehsilOptions += $item->tehsils->pluck('title','id')->toArray();
                                        }
                                        $tehsilSelected = old('tehsil_ids', $item && $item->tehsils ? $item->tehsils->pluck('id')->toArray() : []);
                                    @endphp
                                    {!! Form::select('tehsil_ids[]', $tehsilOptions, $tehsilSelected, ['class' => 'dynamic-select', 'multiple' => 'multiple', 'id' => 'tehsil_ids', 'data-route' => route('dynamic.dropDown'), 'data-statment' => \Illuminate\Support\Facades\Crypt::encrypt(['model' => 'tehsils', 'connection' => 'mysql', 'label' => 'title', 'value' => 'id']), 'data-conditions' => json_encode(['column' => 'district_id', 'operator' => 'in', 'value' => 'district_ids'])]) !!}
                                </div>
                            </div>

                            <div class="col-12 col-sm-6">
                                <div class="form-group">
                                    {!! Form::label('union_council_ids', 'Select Union Councils', ['class' => 'control-label']) !!}
                                    <div class="mb-2">
                                        <button type="button" class="btn btn-sm btn-outline-primary" id="selectAllUnionCouncils">
                                            <i class="bx bx-check-square"></i> Select All Union Councils
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary" id="clearAllUnionCouncils">
                                            <i class="bx bx-x"></i> Clear All
                                        </button>
                                        <small class="text-muted d-block">Requires tehsils to be selected first</small>
                                    </div>
                                    <span class="help">@if(Session::has('errors')) {!! Session::get('errors')->first('union_council_ids') !!} @endif</span>
                                    @php
                                        $ucOptions = ($oldUcs ?? collect())->toArray();
                                        if($item && $item->unioncouncils){
                                            $ucOptions += $item->unioncouncils->pluck('name','id')->toArray();
                                        }
                                        $ucSelected = old('union_council_ids', $item && $item->unioncouncils ? $item->unioncouncils->pluck('id')->toArray() : []);
                                    @endphp
                                    {!! Form::select('union_council_ids[]', $ucOptions, $ucSelected, ['class' => 'dynamic-select','id'=>'union_council_ids', 'multiple' => 'multiple', 'data-route' => route('dynamic.dropDown'), 'data-statment' => \Illuminate\Support\Facades\Crypt::encrypt(['model' => 'union_councillors', 'connection' => 'mysql', 'label' => 'name', 'value' => 'id','concat_column'=>'ur_name']), 'data-conditions' => json_encode(['column' => 'tehsil_id', 'operator' => 'in', 'value' => 'tehsil_ids'])]) !!}
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    {!! Form::label('vc_ids', 'Select Village', ['class' => 'control-label']) !!}
                                    <div class="mb-2">
                                        <button type="button" class="btn btn-sm btn-outline-primary" id="selectAllVillages">
                                            <i class="bx bx-check-square"></i> Select All Villages
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary" id="clearAllVillages">
                                            <i class="bx bx-x"></i> Clear All
                                        </button>
                                        <small class="text-muted d-block">⚠️ Large dataset - may take time to load</small>
                                    </div>
                                    <span class="help">@if(Session::has('errors')) {!! Session::get('errors')->first('vc_ids') !!} @endif</span>
                                    @php
                                        $vcOptions = ($oldVcs ?? collect())->toArray();
                                        if($item && $item->ncVcLists){
                                            $vcOptions += $item->ncVcLists->pluck('name','id')->toArray();
                                        }
                                        $vcSelected = old('vc_ids', $item && $item->ncVcLists ? $item->ncVcLists->pluck('id')->toArray() : []);
                                    @endphp
                                    {!! Form::select('vc_ids[]', $vcOptions, $vcSelected, ['id'=>'vc_ids','class' => 'dynamic-select', 'multiple' => 'multiple', 'data-route' => route('dynamic.dropDown'), 'data-statment' => \Illuminate\Support\Facades\Crypt::encrypt(['model' => 'uc_vc_lists', 'connection' => 'mysql', 'label' => 'name', 'value' => 'id']), 'data-conditions' => json_encode(['column' => 'union_council_id', 'operator' => 'in','value' => 'union_council_ids'])]) !!}
                                </div>
                            </div>

                            <div class="col-6 d-flex">
                                <div class="form-group">
                                    {!! Form::label('is_otp_enabled', 'Enable OTP', ['class' => 'form-check-label']) !!}
                                    <div class="form-check">
                                        {!! Form::checkbox('is_otp_enabled', 1, ($item && $item->is_otp_enabled ? $item->is_otp_enabled : null), ['class' => 'form-check-input', 'id' => 'is_otp_enabled']) !!}
                                        {!! Form::label('is_otp_enabled', 'Yes', ['class' => 'form-check-label']) !!}
                                    </div>
                                    <span class="help">@if(session()->has('errors')) {!! session()->get('errors')->first('is_otp_enabled') !!}@endif</span>
                                </div>

                                <div class="form-group" style="margin-left: 40px;">
                                    {!! Form::label('is_pincode_enabled', 'Enable PINCODE', ['class' => 'form-check-label']) !!}
                                    <div class="form-check">
                                        {!! Form::checkbox('is_pincode_enabled', 1, ($item && $item->is_pincode_enabled ? $item->is_pincode_enabled : null), ['class' => 'form-check-input', 'id' => 'is_pincode_enabled']) !!}
                                        {!! Form::label('is_pincode_enabled', 'Yes', ['class' => 'form-check-label']) !!}
                                    </div>
                                    <span class="help">@if(session()->has('errors')) {!! session()->get('errors')->first('is_pincode_enabled') !!}@endif</span>
                                </div>

                                @if($item && !empty($item->pincode))
                                <div class="form-group" style="margin-left: 40px;">
                                    {!! Form::label('reset_pincode', 'Reset PinCode', ['class' => 'form-check-label']) !!}
                                    <div class="form-check">
                                        {!! Form::checkbox('reset_pincode', 1,null, ['class' => 'form-check-input', 'id' => 'reset_pincode']) !!}
                                        {!! Form::label('reset_pincode', 'Yes', ['class' => 'form-check-label']) !!}
                                    </div>
                                    <span class="help">@if(session()->has('errors')) {!! session()->get('errors')->first('reset_pincode') !!}@endif</span>
                                </div>
                                @endif
                            </div>
                        </div> --}}
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="description" class="form-label">Details </label>
                                    <span class="help">@if(session()->has('errors')) {!! session()->get('errors')->first('description') !!}@endif</span>
                                    <textarea name="description" class="form-control" id="description">{{ old('description', $item->description ?? null) }}</textarea>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="profile_picture" class="form-label">Profile Picture</label>
                                    @if($item && $item->profile_picture_url)
                                        <div class="mb-2">
                                            <img src="{{ $item->profile_picture_url }}" alt="{{ $item->name }}" style="height:80px;width:80px;object-fit:cover;border-radius:6px;">
                                        </div>
                                    @endif
                                    <input type="file" name="profile_picture" id="profile_picture" accept="image/*" class="form-control">
                                    @error('profile_picture')
                                        <span class="help text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>




                        <div class="row">
                            <div class="col-12">

                                <a href="{{ route('settings.users-mgt.list') }}" class="btn btn-warning">
                                    <i class="bx bx-arrow-back"></i> Back
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
        <!-- /traffic sources -->

    </div>
</div>

@push('scripts')

<script>

$(document).ready(function () {
    if ($('#userForm').length) {
        $('#userForm').on('submit', function (e) {
            if ($('#username').val().length < 6) {
                e.preventDefault();
                $('#username_error').text('Username must be at least 6 characters long');
                $('#username').addClass('is-invalid');
            }
        });
        $('#username').on('input', function () {
            if ($(this).val().length >= 6) {
                $('#username_error').text('');
                $(this).removeClass('is-invalid');
            }
        });
    }


    $('#password').on('input', function () {
        let password = $(this).val();
        let strengthText = '';
        let strengthColor = '';

        if (password.length < 6) {
            strengthText = 'Too short';
            strengthColor = 'red';
        } else if (!/[A-Z]/.test(password) || !/[0-9]/.test(password) || !/[!@#\$%\^&\*]/.test(password)) {
            strengthText = 'Weak (add uppercase, numbers, special characters)';
            strengthColor = 'orange';
        } else {
            strengthText = 'Strong';
            strengthColor = 'green';
        }

        $('#password-strength').text(strengthText).css('color', strengthColor);
    });

    $('#password, #password_confirmation').on('input', function () {
        let pass = $('#password').val();
        let confirm = $('#password_confirmation').val();

        if (confirm.length > 0) {
            if (pass === confirm) {
                $('#match-message').text('Passwords match').css('color', 'green');
            } else {
                $('#match-message').text('Passwords do not match').css('color', 'red');
            }
        } else {
            $('#match-message').text('');
        }
    });
});

  document.getElementById('toggle-password').addEventListener('click', function() {
    const pwdInput = document.getElementById('password');
    const icon    = this.querySelector('i');
    if (pwdInput.type === 'password') {
      pwdInput.type = 'text';
      icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
      pwdInput.type = 'password';
      icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
  });

  document.getElementById('toggle-password-confirmation')
    .addEventListener('click', function() {
      const input = document.getElementById('password_confirmation');
      const icon  = this.querySelector('i');
      if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
      } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
      }
  });

  // Select All / Clear All functionality for select2 AJAX dropdowns (Optimized for large datasets)
  $('#selectAllDistricts').on('click', function() {
    var btn = $(this);
    btn.prop('disabled', true).text('Loading...');

    // First get count to show progress
    $.ajax({
      url: "{{ route('dynamic.dropDown') }}",
      type: 'GET',
      data: {
        token: "{!! \Illuminate\Support\Facades\Crypt::encrypt(['model' => 'districts', 'connection' => 'mysql', 'label' => 'title', 'value' => 'id']) !!}",
        page: 1,
        per_page: 50 // Get in batches
      },
      success: function(response) {
        var totalPages = response.last_page || 1;
        var allOptions = [];
        var loadedPages = 0;

        // Load all pages
        for(var page = 1; page <= totalPages; page++) {
          loadPage(page, totalPages);
        }

        function loadPage(pageNum, total) {
          $.ajax({
            url: "{{ route('dynamic.dropDown') }}",
            type: 'GET',
            data: {
              token: "{!! \Illuminate\Support\Facades\Crypt::encrypt(['model' => 'districts', 'connection' => 'mysql', 'label' => 'title', 'value' => 'id']) !!}",
              page: pageNum,
              per_page: 50
            },
            success: function(pageResponse) {
              allOptions = allOptions.concat(pageResponse.data);
              loadedPages++;

              // Update progress
              btn.text('Loading... (' + loadedPages + '/' + total + ')');

              // When all pages loaded
              if(loadedPages === total) {
                $('#district_ids').empty();
                var selectedValues = [];
                $.each(allOptions, function(index, item) {
                  var option = new Option(item.label, item.value, true, true);
                  $('#district_ids').append(option);
                  selectedValues.push(item.value);
                });

                // Properly set the selected values for Select2
                $('#district_ids').val(selectedValues).trigger('change');
                btn.prop('disabled', false).html('<i class="bx bx-check-square"></i> Select All Districts');
              }
            },
            error: function() {
              btn.prop('disabled', false).text('Select All Districts');
              alert('Error loading districts');
            }
          });
        }
      },
      error: function() {
        btn.prop('disabled', false).text('Select All Districts');
        alert('Error loading districts');
      }
    });
  });

  $('#clearAllDistricts').on('click', function() {
    $('#district_ids').val(null).trigger('change');
  });

  $('#selectAllTehsils').on('click', function() {
    var selectedDistricts = $('#district_ids').val();
    if (!selectedDistricts || selectedDistricts.length === 0) {
      alert('Please select districts first');
      return;
    }

    var btn = $(this);
    btn.prop('disabled', true).text('Loading...');

    loadAllWithCondition(
      "{!! \Illuminate\Support\Facades\Crypt::encrypt(['model' => 'tehsils', 'connection' => 'mysql', 'label' => 'title', 'value' => 'id']) !!}",
      'district_id',
      selectedDistricts.join(','),
      '#tehsil_ids',
      btn,
      'Select All Tehsils'
    );
  });

  $('#clearAllTehsils').on('click', function() {
    $('#tehsil_ids').val(null).trigger('change');
  });

  $('#selectAllUnionCouncils').on('click', function() {
    var selectedTehsils = $('#tehsil_ids').val();
    if (!selectedTehsils || selectedTehsils.length === 0) {
      alert('Please select tehsils first');
      return;
    }

    var btn = $(this);
    btn.prop('disabled', true).text('Loading...');

    loadAllWithCondition(
      "{!! \Illuminate\Support\Facades\Crypt::encrypt(['model' => 'union_councillors', 'connection' => 'mysql', 'label' => 'name', 'value' => 'id', 'concat_column' => 'ur_name']) !!}",
      'tehsil_id',
      selectedTehsils.join(','),
      '#union_council_ids',
      btn,
      'Select All Union Councils'
    );
  });

  $('#clearAllUnionCouncils').on('click', function() {
    $('#union_council_ids').val(null).trigger('change');
  });

  $('#selectAllVillages').on('click', function() {
    var selectedUCs = $('#union_council_ids').val();
    if (!selectedUCs || selectedUCs.length === 0) {
      alert('Please select union councils first');
      return;
    }

    var btn = $(this);
    btn.prop('disabled', true).text('Loading Villages...');

    // For villages, we'll use a different approach - show confirmation first
    var confirmed = confirm('This will load all villages for selected union councils. This might take a moment. Continue?');
    if (!confirmed) {
      btn.prop('disabled', false).text('Select All Villages');
      return;
    }

    loadAllWithCondition(
      "{!! \Illuminate\Support\Facades\Crypt::encrypt(['model' => 'uc_vc_lists', 'connection' => 'mysql', 'label' => 'name', 'value' => 'id']) !!}",
      'union_council_id',
      selectedUCs.join(','),
      '#vc_ids',
      btn,
      'Select All Villages'
    );
  });

  $('#clearAllVillages').on('click', function() {
    $('#vc_ids').val(null).trigger('change');
  });

  // Generic function to load all records with condition in batches
  function loadAllWithCondition(token, whereColumn, whereValue, targetSelect, button, originalText) {
    $.ajax({
      url: "{{ route('dynamic.dropDown') }}",
      type: 'GET',
      data: {
        token: token,
        'where[column]': whereColumn,
        'where[operator]': 'in',
        'where[value]': whereValue,
        page: 1,
        per_page: 100 // Larger batch size for filtered results
      },
      success: function(response) {
        var totalPages = response.last_page || 1;
        var allOptions = [];
        var loadedPages = 0;

        // If only one page, load directly
        if (totalPages === 1) {
          $(targetSelect).empty();
          var selectedValues = [];
          $.each(response.data, function(index, item) {
            var option = new Option(item.label, item.value, true, true);
            $(targetSelect).append(option);
            selectedValues.push(item.value);
          });

          // Properly set the selected values for Select2
          $(targetSelect).val(selectedValues).trigger('change');
          button.prop('disabled', false).html('<i class="bx bx-check-square"></i> ' + originalText.replace('Select All ', ''));
          return;
        }

        // Load all pages for multiple pages
        for(var page = 1; page <= totalPages; page++) {
          loadFilteredPage(page, totalPages);
        }

        function loadFilteredPage(pageNum, total) {
          $.ajax({
            url: "{{ route('dynamic.dropDown') }}",
            type: 'GET',
            data: {
              token: token,
              'where[column]': whereColumn,
              'where[operator]': 'in',
              'where[value]': whereValue,
              page: pageNum,
              per_page: 100
            },
            success: function(pageResponse) {
              allOptions = allOptions.concat(pageResponse.data);
              loadedPages++;

              // Update progress
              button.text('Loading... (' + loadedPages + '/' + total + ')');

              // When all pages loaded
              if(loadedPages === total) {
                $(targetSelect).empty();
                var selectedValues = [];
                $.each(allOptions, function(index, item) {
                  var option = new Option(item.label, item.value, true, true);
                  $(targetSelect).append(option);
                  selectedValues.push(item.value);
                });

                // Properly set the selected values for Select2
                $(targetSelect).val(selectedValues).trigger('change');
                button.prop('disabled', false).html('<i class="bx bx-check-square"></i> ' + originalText.replace('Select All ', ''));
              }
            },
            error: function() {
              button.prop('disabled', false).text(originalText);
              alert('Error loading data');
            }
          });
        }
      },
      error: function() {
        button.prop('disabled', false).text(originalText);
        alert('Error loading data');
      }
    });
  }

</script>
@endpush

@endsection
