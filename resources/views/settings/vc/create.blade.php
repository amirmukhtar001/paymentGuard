@extends('layouts.' . config('settings.active_layout'))

@section('content')
    <div class="row">
        <!-- Website Analytics-->
        <div class="col-12 mb-4">

            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Create Village</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('settings.vc.store') }}" method="post">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name">
                        </div>

                        <div class="mb-3">
                            <label for="ur_name" class="form-label req">Urdu Name</label>
                            <input type="text" name="ur_name" value="{{ old('ur_name') }}" class="form-control" placeholder="Enter Urdu name" required>
                            <span class="help">
                                @if ($errors->has('ur_name'))
                                    {!! $errors->first('ur_name') !!}
                                @endif
                            </span>
                        </div>

                        <div class="mb-3">
                            <label for="type" class="form-label">Type</label>
                            <select class="form-select" id="type" name="type">
                                <option value="VC">Village Council</option>
                                <option value="NC">Neighborhood Council</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <div class="form-group">
                                <label for="district_id" class="fw-semibold req">Select a District <span class="float-end fw-semibold req">ضلع کا انتخاب کریں</span></label>
                                <span class="help">
                                    @if (Session::has('errors'))
                                        {!! Session::get('errors')->first('district_id') !!}
                                    @endif
                                </span>
                                <select name="district_id" class="dynamic-select" placeholder="Select a district" data-route="{{ route('dynamic.dropDown') }}" data-statment="{{ \Illuminate\Support\Facades\Crypt::encrypt(['model' => 'districts', 'connection' => 'mysql', 'label' => 'title', 'value' => 'id']) }}">
                                    <option value>Select a District</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-group">
                                <label for="tehsil_id" class="fw-semibold req">Select a Tehsil <span class="control-label float-end fw-semibold req">تحصیل کا انتخاب کریں</span></label>

                                <span class="help">
                                    @if (Session::has('errors'))
                                        {!! Session::get('errors')->first('tehsil_id') !!}
                                    @endif
                                </span>
                                <select name="tehsil_id" class="dynamic-select" placeholder="Select a Tehsil" data-route="{{ route('dynamic.dropDown') }}" data-statment="{{ \Illuminate\Support\Facades\Crypt::encrypt(['model' => 'tehsils', 'connection' => 'mysql', 'label' => 'title', 'value' => 'id']) }}" data-conditions="{{ json_encode(['column' => 'district_id', 'operator' => '=', 'value' => 'district_id']) }}">
                                    <option value>Select a Tehsil</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-group">
                                <label for="union_council_id" class="fw-semibold req">Union Counil <span class="float-end fw-semibold req">یونین کونسل</span></label>
                                <span class="help">
                                    @if (Session::has('errors'))
                                        {!! Session::get('errors')->first('union_council_id') !!}
                                    @endif
                                </span>
                                <select name="union_council_id" class="dynamic-select" placeholder="Select a Union Counil" data-route="{{ route('dynamic.dropDown') }}" data-statment="{{ \Illuminate\Support\Facades\Crypt::encrypt(['model' => 'union_councillors', 'connection' => 'mysql', 'label' => 'name', 'value' => 'id', 'concat_column' => 'ur_name']) }}" data-conditions="{{ json_encode(['column' => 'tehsil_id', 'operator' => '=', 'value' => 'tehsil_id']) }}" data-tags="{{ config('agriculture.allow_custom.union_council_id') }}">
                                    <option value>Select a Union Counil</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">

                                <a href="{{ route('settings.vc.list') }}" class="btn btn-warning">
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
@endsection

@push('scripts')
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>

    <script src="{{ asset('assets/js/Settings/drop-down.js') }}"></script>
@endpush
