@extends('layouts.' . config('settings.active_layout'))

@section('content')
    <div class="row">
        <div class="col-12">
            <!-- Traffic sources -->
            <div class="card">
                <div class="card-header header-elements-inline">
                    <h6 class="card-title">{{ $title }}</h6>
                    <div class="header-elements">
                    </div>
                    <div class="text-right" style="text-align: right"></div>
                </div>
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-12 col-lg-12">
                            <form action="{{ route('settings.users-mgt.my-profile-save') }}" method="post" id="userForm">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name" class="form-label req">Officer Title </label>
                                            <span class="help">
                                                @if (session()->has('errors'))
                                                    {!! session()->get('errors')->first('name') !!}
                                                @endif
                                            </span>
                                            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control" id="name" disabled>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email" class="form-label req">Email Address </label>
                                            <span class="help">
                                                @if (session()->has('errors'))
                                                    {!! session()->get('errors')->first('email') !!}
                                                @endif
                                            </span>
                                            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" id="email" required>
                                        </div>
                                    </div>

                                </div>

                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="username" class="form-label req">Username </label>
                                            <span class="help">
                                                @if (session()->has('errors'))
                                                    {!! session()->get('errors')->first('username') !!}
                                                @endif
                                            </span>
                                            <input type="text" name="username" value="{{ old('username', $user->username) }}" class="form-control" id="username" required>
                                            <div id="username_error" class="text-danger"></div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password" class="form-label req">Current Password </label>
                                            <span class="help">
                                                @if (session()->has('errors'))
                                                    {!! session()->get('errors')->first('password') !!}
                                                @endif
                                            </span>
                                            {{-- {!! Form::password('password', null, ['class' => 'form-control', 'id' => 'password', 'required' => 'required']) !!} --}}
                                            <input type="password" id="password" name="password" class="form-control"
                                                required>
                                            <span class="-help text-primary">Please provide your current password to save
                                                changes</span>
                                        </div>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-12">

                                        <button class="btn btn-danger">
                                            <i class="icon-undo mr-1"></i> Reset
                                        </button>

                                        <button type="submit" class="btn btn-success">
                                            <i class="icon-database-check mr-1"></i> Save Profile
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

@push('scripts')
    <script>
        $(document).ready(function() {
            if ($('#userForm').length) {
                $('#userForm').on('submit', function(e) {
                    if ($('#username').val().length < 6) {
                        e.preventDefault();
                        $('#username_error').text('Username must be at least 6 characters long');
                        $('#username').addClass('is-invalid');
                    }
                });
                $('#username').on('input', function() {
                    const usernameVal = $(this).val();
                    if (usernameVal.length < 6) {
                        $('#username_error').text('Username must be at least 6 characters long');
                        $(this).addClass('is-invalid');
                    } else {
                        $('#username_error').text('');
                        $(this).removeClass('is-invalid');
                    }
                });
            }
        });
    </script>
@endpush
