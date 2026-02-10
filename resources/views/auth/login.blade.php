@extends('layouts.auth')

@section('content')
<div class="container-xxl bottom-border-line">
    <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner py-2">
            <!-- Login Card -->
            <div class="card">
                <div class="card-body">
                    <!-- Logo -->
                    <div class="app-brand justify-content-center">
                        <a href="#" class="app-brand-link gap-2">
                            <span class="app-brand-text demo h3 mb-0 fw-bold">
                                <!-- <img src="{{ site_logo() }}" width="230"> -->
                                <img src="{{ asset('assets/img/kp_logo.png') }}" width="230">
                            </span>
                        </a>
                    </div>
                    <!-- /Logo -->

                    @if (session()->has('error'))
                    <div class="alert alert-danger">
                        {{ session()->get('error') }}
                    </div>
                    @endif

                    <form id="formAuthentication" class="mb-3" action="{{ route('custom-authenticate') }}"
                        method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">Username</label>
                            <input type="text" class="form-control" id="email" name="username"
                                placeholder="Enter your email or username" autofocus />
                        </div>

                        <div class="mb-3 form-password-toggle">
                            <div class="d-flex justify-content-between">
                                <label class="form-label" for="password">Password</label>
                            </div>
                            <div class="input-group input-group-merge">
                                <input type="password" id="password" class="form-control" name="password"
                                    placeholder="••••••••••••" aria-describedby="password" />
                                <span class="input-group-text cursor-pointer" id="togglePassword">
                                    <i class="bx bx-hide"></i>
                                </span>

                            </div>
                        </div>

                        <div class="mb-3">
                            <button class="btn btn-primary d-grid w-100 bg-green-new" type="submit">Sign in</button>
                        </div>
                    </form>
                    <div class="d-flex justify-content-end mb-3">
                        <a href="{{ route('password.forgot.view') }}"><small>Forgot Password?</small></a>
                    </div>

                    <h3 class="text-center an-initiative-on">Kp Web Portal Login</h3>
                </div>
            </div>
            <!-- /Login Card -->

            <!-- Footer -->
            <footer class="content-footer footer bg-footer-theme mt-4">
                <div
                    class="container-xxl d-flex flex-wrap justify-content-center py-2 flex-md-row flex-column mb-2 mb-md-0">
                    <div class="mb-2 mb-md-0">
                        <h3 class="text-center an-initiative-on">A Project by</h3>
                        <!-- <img src="{{ asset('assets/img/itboardlogo.png') }}" width="900" /> -->
                    </div>
                </div>
            </footer>
            <!-- / Footer -->
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const togglePassword = document.getElementById("togglePassword");
        const passwordInput = document.getElementById("password");
        const icon = togglePassword.querySelector("i");

        togglePassword.addEventListener("click", function() {
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                icon.classList.remove("bx-hide");
                icon.classList.add("bx-show");
            } else {
                passwordInput.type = "password";
                icon.classList.remove("bx-show");
                icon.classList.add("bx-hide");
            }
        });
    });
</script>

@endpush