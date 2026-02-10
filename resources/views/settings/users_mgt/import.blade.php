@extends('layouts.'.config('settings.active_layout'))

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

                        <form enctype="multipart/form-data" method="POST" action="{{ route('settings.users-mgt.import.importUsers') }}">
                            @csrf

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="upload_file" class="form-label req">File CSV</label>
                                    <span class="help">@if(session()->has('errors')) {!! session()->get('errors')->first('upload_file') !!}@endif</span>
                                    <input type="file" name="upload_file" class="form-control" id="upload_file" required>
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-12">

                                <a href="{{ route('settings.users-mgt.list') }}" class="btn btn-warning">
                                    <i class="bx bx-arrow-back"></i> Back
                                </a>

                                <button type="submit" class="btn btn-success">
                                    <i class="bx bx-save"></i> Import
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
