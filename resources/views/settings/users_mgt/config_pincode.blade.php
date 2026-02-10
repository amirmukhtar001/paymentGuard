{{--@extends('layouts.'.config('settings.active_layout'))--}}
@extends('layouts.'.config('eidentity.active_layout'))

@section('content')

<div class="row">
    <div class="col-12">

        <!-- Traffic sources -->
        <div class="card">
            <div class="card-header header-elements-inline">
                <h5 class="card-title">{{ $title }}</h5>
                <div class="header-elements">
                    <div class="form-check form-check-right form-check-switchery form-check-switchery-sm">
                    </div>
                </div>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-12">

                        <form action="{{ route('settings.users-mgt.config-pincode-save') }}" method="post">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pincode" class="form-label req">Enter Your New Pin Code</label>
                                        <span class="help">@if(session()->has('errors')) {!! session()->get('errors')->first('pincode') !!} @endif</span>
                                        <input type="password" name="pincode" id="pincode" pattern="\d{4}" title="Please enter a 4-digit PIN code" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="current_password" class="form-label req">Current Password</label>
                                        <span class="help">@if(session()->has('errors')) {!! session()->get('errors')->first('current_password') !!} @endif</span>
                                        <input type="password" name="current_password" id="current_password" class="form-control" required>
                                        <span class="-help text-primary">Please provide your current password to save changes</span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">

                                    <button class="btn btn-danger">
                                        <i class="icon-undo mr-1"></i> Reset
                                    </button>

                                    <button type="submit" class="btn btn-success">
                                        <i class="icon-database-check mr-1"></i> Save Changes
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