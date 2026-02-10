@extends('layouts.' . config('settings.active_layout'))

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-tabs mb-3" id="settingsTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="email-tab" data-bs-toggle="tab" data-bs-target="#email"
                                type="button" role="tab">Email Settings</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="twilio-tab" data-bs-toggle="tab" data-bs-target="#twilio"
                                type="button" role="tab">Twilio Settings</button>
                        </li>
                        {{-- <li class="nav-item" role="presentation">
                            <button class="nav-link" id="sms-tab" data-bs-toggle="tab" data-bs-target="#sms"
                                type="button" role="tab">Jazz SMS Settings</button>
                        </li> --}}
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="features-tab" data-bs-toggle="tab" data-bs-target="#features"
                                type="button" role="tab">Feature Toggles</button>
                        </li>

                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="website-tab" data-bs-toggle="tab" data-bs-target="#website"
                                type="button" role="tab">Website</button>
                        </li>
                    </ul>

                    <form method="POST" action="{{ route('settings.save') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="tab-content" id="settingsTabContent">
                            <div class="tab-pane fade show active" id="email" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="mail_driver" class="form-label">Mail Driver</label>
                                            <select name="mail_driver" class="form-select">
                                                <option value="smtp"
                                                    {{ setting('mail_driver') === 'smtp' ? 'selected' : '' }}>SMTP</option>
                                                <option value="sendmail"
                                                    {{ setting('mail_driver') === 'sendmail' ? 'selected' : '' }}>Sendmail
                                                </option>
                                                <option value="mailgun"
                                                    {{ setting('mail_driver') === 'mailgun' ? 'selected' : '' }}>Mailgun
                                                </option>
                                                <option value="ses"
                                                    {{ setting('mail_driver') === 'ses' ? 'selected' : '' }}>Amazon SES
                                                </option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="mail_host" class="form-label">Mail Host</label>
                                            <input type="text" name="mail_host" class="form-control"
                                                placeholder="smtp.mailtrap.io" value="{{ setting('mail_host') }}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="mail_port" class="form-label">Mail Port</label>
                                            <input type="text" name="mail_port" class="form-control" placeholder="2525"
                                                value="{{ setting('mail_port') }}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="mail_encryption" class="form-label">Encryption</label>
                                            <select name="mail_encryption" class="form-select">
                                                <option value="tls"
                                                    {{ setting('mail_encryption') === 'tls' ? 'selected' : '' }}>TLS
                                                </option>
                                                <option value="ssl"
                                                    {{ setting('mail_encryption') === 'ssl' ? 'selected' : '' }}>SSL
                                                </option>
                                                <option value=""
                                                    {{ setting('mail_encryption') === '' ? 'selected' : '' }}>None</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="mail_username" class="form-label">Mail Username</label>
                                            <input type="text" name="mail_username" class="form-control"
                                                placeholder="your_email@example.com"
                                                value="{{ setting('mail_username') }}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="mail_password" class="form-label">Mail Password</label>
                                            <input type="password" name="mail_password" class="form-control"
                                                placeholder="******" value="{{ setting('mail_password') }}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="mail_from_address" class="form-label">From Email Address</label>
                                            <input type="text" name="mail_from_address" class="form-control"
                                                placeholder="noreply@example.com"
                                                value="{{ setting('mail_from_address') }}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="mail_from_name" class="form-label">From Name</label>
                                            <input type="text" name="mail_from_name" class="form-control"
                                                placeholder="Agriculture Department"
                                                value="{{ setting('mail_from_name') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="twilio" role="tabpanel">

                                <div class="mb-3">
                                    <label>Twilio URL</label>
                                    <input type="text" class="form-control" name="twilio_url"
                                        value="{{ setting('twilio_url') }}">
                                </div>

                                <div class="mb-3">
                                    <label>Twilio SID</label>
                                    <input type="text" class="form-control" name="twilio_sid"
                                        value="{{ setting('twilio_sid') }}">
                                </div>
                                <div class="mb-3">
                                    <label>Twilio Token</label>
                                    <input type="text" class="form-control" name="twilio_token"
                                        value="{{ setting('twilio_token') }}">
                                </div>
                                <div class="mb-3">
                                    <label>Twilio From</label>
                                    <input type="text" class="form-control" name="twilio_from"
                                        value="{{ setting('twilio_from') }}">
                                </div>
                            </div>

                            <div class="tab-pane fade" id="sms" role="tabpanel">
                                <div class="mb-3">
                                    <label>Jazz Username</label>
                                    <input type="text" class="form-control" name="jazz_username"
                                        value="{{ setting('jazz_username') }}">
                                </div>
                                <div class="mb-3">
                                    <label>Jazz Password</label>
                                    <input type="text" class="form-control" name="jazz_password"
                                        value="{{ setting('jazz_password') }}">
                                </div>
                                <div class="mb-3">
                                    <label>Jazz Sender</label>
                                    <input type="text" class="form-control" name="jazz_sender"
                                        value="{{ setting('jazz_sender') }}">
                                </div>
                            </div>

                            <div class="tab-pane fade" id="features" role="tabpanel">

                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="enable_register_email"
                                        {{ setting('enable_register_email') ? 'checked' : '' }}>
                                    <label class="form-check-label">Send Email on New User Register</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="enable_email_forgot"
                                        {{ setting('enable_email_forgot') ? 'checked' : '' }}>
                                    <label class="form-check-label">Enable Email for Forgot Password</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="enable_sms_forgot"
                                        {{ setting('enable_sms_forgot') ? 'checked' : '' }}>
                                    <label class="form-check-label">Enable SMS for Forgot Password</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="enable_login_otp"
                                        {{ setting('enable_login_otp') ? 'checked' : '' }}>
                                    <label class="form-check-label">Enable Login OTP</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="enable_farmer_register_sms"
                                        {{ setting('enable_farmer_register_sms') ? 'checked' : '' }}>
                                    <label class="form-check-label">Send SMS on Farmer Registration Complete</label>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="website" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="play_store_link" class="form-label">Play Store Link</label>
                                            <input type="text" name="play_store_link" class="form-control"
                                                placeholder="www.example.com/app"
                                                value="{{ setting('play_store_link') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="app_store_link" class="form-label">App Store Link</label>
                                            <input type="text" name="app_store_link" class="form-control"
                                                placeholder="www.example.com/app"
                                                value="{{ setting('app_store_link') }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="row" id="vueapp">
                                    @php
                                        $siteLogoSetting = \App\Models\Setting::where('key', 'site_logo')->first();
                                        $footerLogoSetting = \App\Models\Setting::where('key', 'site_logo_bottom')->first();
                                    @endphp
                                    <div class="col-md-6">
                                        @if($siteLogoSetting && $siteLogoSetting->exists)
                                            <livewire:media-uploader
                                                :for="$siteLogoSetting"
                                                collection="site_logo"
                                                :multiple="false"
                                                accept="image/jpeg,image/png,image/jpg,image/gif"
                                                label="Site Logo"
                                            />
                                        @else
                                            <div class="alert alert-info">
                                                <i class="fas fa-info-circle"></i>
                                                Please create a site_logo setting first.
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-6">
                                        @if($footerLogoSetting && $footerLogoSetting->exists)
                                            <livewire:media-uploader
                                                :for="$footerLogoSetting"
                                                collection="site_logo_bottom"
                                                :multiple="false"
                                                accept="image/jpeg,image/png,image/jpg,image/gif"
                                                label="Footer Logo"
                                            />
                                        @else
                                            <div class="alert alert-info">
                                                <i class="fas fa-info-circle"></i>
                                                Please create a site_logo_bottom setting first.
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="site_title" class="form-label">Site Title</label>
                                            <input type="text" name="site_title" class="form-control"
                                                value="{{ setting('site_title') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="site_email" class="form-label">Email</label>
                                            <input type="text" name="site_email" class="form-control"
                                                value="{{ setting('site_email') }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="contact_no" class="form-label">Contact Number</label>
                                            <input type="text" name="contact_no" class="form-control"
                                                value="{{ setting('contact_no') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="call_center_no" class="form-label">Call Center Number</label>
                                            <input type="text" name="call_center_no" class="form-control"
                                                value="{{ setting('call_center_no') }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="address" class="form-label">Address</label>
                                            <input type="text" name="address" class="form-control"
                                                value="{{ setting('address') }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="facebook_link" class="form-label">Facebook</label>
                                            <input type="text" name="facebook_link" class="form-control"
                                                value="{{ setting('facebook_link') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="twitter_link" class="form-label">Twitter</label>
                                            <input type="text" name="twitter_link" class="form-control"
                                                value="{{ setting('twitter_link') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="instagram_link" class="form-label">Instagram</label>
                                            <input type="text" name="instagram_link" class="form-control"
                                                value="{{ setting('instagram_link') }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="youtube_link" class="form-label">YouTube</label>
                                            <input type="text" name="youtube_link" class="form-control"
                                                value="{{ setting('youtube_link') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="linkedin_link" class="form-label">LinkedIn</label>
                                            <input type="text" name="linkedin_link" class="form-control"
                                                value="{{ setting('linkedin_link') }}">
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary mt-3">Save Settings</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
