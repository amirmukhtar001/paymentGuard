@extends('layouts.auth')

@section('content')
    <div class="container-xxl bottom-border-line">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner py-2">
                <!-- Forgot Password Card -->
                <div class="card">
                    <div class="card-body">
                        <!-- Logo -->
                        <div class="app-brand justify-content-center">
                            <a href="#" class="app-brand-link gap-2">
                                <span class="app-brand-text demo h3 mb-0 fw-bold">
                                    <img src="{{ asset('assets/img/kp_logo.png') }}" width="230">
                                </span>
                            </a>
                        </div>
                        <!-- /Logo -->

                        <h4 class="text-center mb-4">Forgot Password</h4>

                        <div id="responseAlert"></div>

                        {{-- Step 1: Enter Email or Phone --}}
                        <form id="sendOtpForm" class="mb-3">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Enter Email</label>
                                <input type="text" name="identity" class="form-control" placeholder="example@example.com"
                                    required>
                            </div>
                            <button class="btn btn-primary d-grid w-100 bg-green-new">Send OTP</button>
                        </form>

                        {{-- Step 2: Verify OTP --}}
                        <form id="verifyOtpForm" class="mb-3 d-none">
                            @csrf
                            <input type="hidden" name="user_id" id="otpUserId">
                            <div class="mb-3">
                                <label class="form-label">Enter OTP</label>
                                <input type="text" name="otp" class="form-control" placeholder="Enter the OTP you received"
                                    required>
                            </div>
                            <button class="btn btn-success d-grid w-100">Verify OTP</button>
                        </form>

                        {{-- Step 3: Reset Password --}}
                        <form id="resetPasswordForm" class="mb-3 d-none">
                            @csrf
                            <input type="hidden" name="user_id" id="resetUserId">
                            <div class="mb-3">
                                <label class="form-label">New Password</label>
                                <input type="password" name="password" class="form-control" placeholder="New Password"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Confirm Password</label>
                                <input type="password" name="password_confirmation" class="form-control"
                                    placeholder="Confirm Password" required>
                            </div>
                            <button class="btn btn-warning d-grid w-100">Reset Password</button>
                        </form>

                        <div class="text-center mt-3">
                            <a href="{{ route('login') }}"><small>Back to Login</small></a>
                        </div>

                        <h3 class="text-center an-initiative-on mt-4"></h3>
                    </div>
                </div>
                <!-- /Forgot Password Card -->
                <!-- Footer -->
                <footer class="content-footer footer bg-footer-theme mt-4">
                    <div
                        class="container-xxl d-flex flex-wrap justify-content-center py-2 flex-md-row flex-column mb-2 mb-md-0">
                        <div class="mb-2 mb-md-0">
                            <h3 class="text-center an-initiative-on"></h3>
                            <!-- <img src="{{ site_logo('bottom') }}" width="300" />-->
                        </div>
                    </div>
                </footer>
                <!-- / Footer -->
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(function () {
                $('#sendOtpForm').on('submit', function (e) {
                    e.preventDefault();
                    let form = $(this);
                    $.post("{{ route('password.send.otp') }}", form.serialize(), function (res) {
                        if (!res.error) {
                            $('#otpUserId').val(res.data.user_id);
                            $('#resetUserId').val(res.data.user_id);
                            $('#sendOtpForm').hide();
                            $('#verifyOtpForm').removeClass('d-none');
                            showAlert('success', res.message);
                        } else {
                            showAlert('danger', res.message);
                        }
                    }).fail(function (err) {
                        showAlert('danger', err.responseJSON.message);
                    });
                });

                $('#verifyOtpForm').on('submit', function (e) {
                    e.preventDefault();
                    let form = $(this);
                    $.post("{{ route('password.verify.otp') }}", form.serialize(), function (res) {
                        if (!res.error) {
                            $('#verifyOtpForm').hide();
                            $('#resetPasswordForm').removeClass('d-none');
                            showAlert('success', res.message);
                        } else {
                            showAlert('danger', res.message);
                        }
                    }).fail(function (err) {
                        showAlert('danger', err.responseJSON.message);
                    });
                });

                $('#resetPasswordForm').on('submit', function (e) {
                    e.preventDefault();
                    let form = $(this);
                    // Post to custom forgot-password reset endpoint (no token required)
                    $.post("{{ url('forget-password/reset') }}", form.serialize(), function (res) {
                        if (!res.error) {
                            showAlert('success', res.message);
                            window.location.href = "{{ route('login') }}";
                        } else {
                            showAlert('danger', res.message);
                        }
                    }).fail(function (err) {
                        showAlert('danger', err.responseJSON.message);
                    });
                });

                function showAlert(type, message) {
                    $('#responseAlert').html(`<div class="alert alert-${type}">${message}</div>`);
                }
            });
        </script>
    @endpush
@endsection
