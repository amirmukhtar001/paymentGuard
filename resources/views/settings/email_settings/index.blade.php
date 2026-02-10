@extends('layouts.app')
@section('content')
<div class="container">
  <h1>Email Settings</h1>
  <table class="table">
    <thead>
      <tr>
        <th>Event</th><th>To Admin</th><th>To User</th><th>CC Emails</th><th></th>
      </tr>
    </thead>
    <tbody>
    @foreach($settings as $s)
      <tr>
        <td>{{ $s->event_key }}</td>
        <td>{{ $s->to_admin ? '✔️':'—' }}</td>
        <td>{{ $s->to_user  ? '✔️':'—' }}</td>
        <td>{{ $s->cc_emails }}</td>
        <td>
          <a href="{{ route('settings.email-settings.edit',$s) }}" class="btn btn-sm btn-primary">Edit</a>
        </td>
      </tr>
    @endforeach
    </tbody>
  </table>
</div>
@endsection
