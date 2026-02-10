@extends('layouts.' . config('settings.active_layout'))

@push('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
@endpush

@push('scripts')
    <script src="{{ asset('assets/vendor/libs/bootstrap-maxlength/bootstrap-maxlength.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/js/Settings/drop-down.js') }}"></script>
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

                            {{-- <label class="form-check-label">
                                Live update:
                                <input type="checkbox" class="form-input-switchery" checked data-fouc>
                            </label> --}}
                        </div>
                    </div>
                </div>

                <div class="card-body">

                    <div class="row">

                        <div class="col-12">

                            {!! Form::model($model, [
                                'enctype' => 'multipart/form-data',
                                'method' => $model->exists ? 'put' : 'post',
                                'route' => $model->exists
                                    ? ['settings.unioncouncillors.update', \Illuminate\Support\Facades\Crypt::encrypt($model->id)]
                                    : ['settings.unioncouncillors.store'],
                            ]) !!}

                            <div class="mb-3">
                                {!! Form::label('district_id', 'Select a District ', ['class' => 'control-label req']) !!}
                                <span class="help">
                                    @if (Session::has('errors'))
                                        {!! Session::get('errors')->first('district_id') !!}
                                    @endif
                                </span>
                                {!! Form::select(
                                    'district_id',
                                    [null => 'Select a District'] +
                                        ($model && $model->district ? [$model?->district?->id ?? 0 => $model?->district?->title ?? ''] : []),
                                    null,
                                    [
                                        'class' => 'dynamic-select',
                                        'placeholder' => 'Select a district',
                                        'data-route' => route('dynamic.dropDown'),
                                        'data-statment' => \Illuminate\Support\Facades\Crypt::encrypt([
                                            'model' => 'districts',
                                            'connection' => 'mysql',
                                            'label' => 'title',
                                            'value' => 'id',
                                        ]),
                                        'required',
                                    ],
                                ) !!}
                            </div>

                            <div class="mb-3">
                                {!! Form::label('tehsil_id', 'Select a Tehsil ', ['class' => 'control-label req']) !!}
                                <span class="help">
                                    @if (Session::has('errors'))
                                        {!! Session::get('errors')->first('tehsil_id') !!}
                                    @endif
                                </span>
                                {!! Form::select(
                                    'tehsil_id',
                                    [null => 'Select a Tehsil'] +
                                        ($model && $model->tehsil ? [$model?->tehsil?->id ?? 0 => $model?->tehsil?->title ?? ''] : []),
                                    null,
                                    [
                                        'class' => 'dynamic-select',
                                        'placeholder' => 'Select a Tehsil',
                                        'data-route' => route('dynamic.dropDown'),
                                        'data-statment' => \Illuminate\Support\Facades\Crypt::encrypt([
                                            'model' => 'tehsils',
                                            'connection' => 'mysql',
                                            'label' => 'title',
                                            'value' => 'id',
                                        ]),
                                        'data-conditions' => json_encode(['column' => 'district_id', 'operator' => '=', 'value' => 'district_id']),
                                        'required',
                                    ],
                                ) !!}
                            </div>

                            <div class="mb-3">
                                {!! Form::label('name', 'Name', ['class' => 'form-label req']) !!}
                                {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Enter Name', 'required']) !!}
                                <span class="help">
                                    @if ($errors->has('name'))
                                        {!! $errors->first('name') !!}
                                    @endif
                                </span>
                            </div>
                            <div class="mb-3">
                                {!! Form::label('ur_name', 'Urdu Name', ['class' => 'form-label req']) !!}
                                {!! Form::text('ur_name', null, ['class' => 'form-control', 'placeholder' => 'Enter Urdu name', 'required']) !!}
                                <span class="help">
                                    @if ($errors->has('ur_name'))
                                        {!! $errors->first('ur_name') !!}
                                    @endif
                                </span>
                            </div>

                            <div class="row">
                                <div class="col-12">

                                    <a href="{{ route('settings.unioncouncillors.list') }}" class="btn btn-warning">
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
            <!-- /traffic sources -->

        </div>
    </div>
@endsection
