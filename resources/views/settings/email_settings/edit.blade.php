@extends('layouts.app')
@section('content')
<div class="container">
  <h1>Edit “{{ $emailSetting->event_key }}”</h1>
  <form action="{{ route('settings.email-settings.update',$emailSetting) }}" method="POST">
    @method('PUT') @csrf

    <div class="form-check mb-2">
      <input type="hidden" name="to_admin" value="0">
      <input type="checkbox" class="form-check-input" id="to_admin" name="to_admin" value="1"
        {{ $emailSetting->to_admin ? 'checked':'' }}>
      <label class="form-check-label" for="to_admin">Send to Admin</label>
    </div>

    <div class="form-check mb-2">
      <input type="hidden" name="to_user" value="0">
      <input type="checkbox" class="form-check-input" id="to_user" name="to_user" value="1"
        {{ $emailSetting->to_user ? 'checked':'' }}>
      <label class="form-check-label" for="to_user">Send to User</label>
    </div>

    <div class="mb-3">
      <label for="cc_emails" class="form-label">CC Emails (comma-separated)</label>
      <input type="text" class="form-control" id="cc_emails" name="cc_emails"
             value="{{ old('cc_emails',$emailSetting->cc_emails) }}">
    </div>

    <button class="btn btn-success">Save</button>
  </form>
</div>
@endsection
