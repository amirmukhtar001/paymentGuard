@extends('layouts.' . config('settings.active_layout'))
@section('content')
    @include('settings.diagnoses.form')
@endsection