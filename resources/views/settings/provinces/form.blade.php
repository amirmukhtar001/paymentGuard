@extends('layouts.'.config('settings.active_layout'))

@push('vendor-style')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
@endpush

@push('scripts')
<script src="{{asset('assets/vendor/libs/bootstrap-maxlength/bootstrap-maxlength.js')}}"></script>
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

                        {!! Form::model($model, [
                        'enctype' => 'multipart/form-data',
                        'method' => $model->exists ? 'put' : 'post',
                        'route' => $model->exists ? ['settings.provinces.update', \Illuminate\Support\Facades\Crypt::encrypt($model->id)] : ['settings.provinces.store']
                        ])
                        !!}

                        {!! Form::hidden('active', '1') !!}

                        <div class="mb-3">
                            {!! Form::label('country_id', 'Select a Country ', ['class' => 'control-label req']) !!}
                            <span class="help">@if(Session::has('errors')) {!! Session::get('errors')->first('country_id') !!} @endif</span>
                            {!! Form::select('country_id',[null=>'Select a Country'] + ($model && $model->countries? [$model?->countries?->id ?? 0 => $model?->countries?->title ?? ''] : []), old('country_id', $model->country_id ?? ''), ['class' => 'dynamic-select','placeholder'=>'Select a countries', 'data-route'=> route('dynamic.dropDown'),'data-statment'=> \Illuminate\Support\Facades\Crypt::encrypt(['model' => 'countries','connection' => 'mysql','label'=>'title','value'=>'id' ]),'required']) !!}
                        </div>

                        <div class="mb-3">
                            {!! Form::label('title', 'Title', ['class' => 'form-label req']) !!}
                            {!! Form::text('title', null, ['class' => 'form-control', 'placeholder' => 'Enter title','required']) !!}
                            <span class="help">@if($errors->has('title')) {!! $errors->first('title') !!} @endif</span>
                        </div>
                        
                        <div class="mb-3">
                            {!! Form::label('abbreviation', 'Abbreviation', ['class' => 'form-label']) !!}
                            {!! Form::text('abbreviation', null, ['class' => 'form-control', 'placeholder' => 'Enter abbreviation']) !!}
                            <span class="help">@if($errors->has('abbreviation')) {!! $errors->first('abbreviation') !!} @endif</span>
                        </div>
                        
                        <div class="mb-3">
                            {!! Form::label('description', 'Description', ['class' => 'form-label']) !!}
                            {!! Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => 'Enter description']) !!}
                            <span class="help">@if($errors->has('description')) {!! $errors->first('description') !!} @endif</span>
                        </div>

                        <div class="row">
                            <div class="col-12">

                                <a href="{{ route('settings.provinces.list') }}" class="btn btn-warning">
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