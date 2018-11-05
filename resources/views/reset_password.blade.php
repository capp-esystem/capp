@extends('layouts.app')

@section('content')
<div class="container main">
  <div class="page-header">
    <h1>Reset Password</h1>
  </div>
  @if ($errors->has('auth'))
  <div class="alert alert-danger" role="alert">{{ $errors->first('auth') }}</div>
  @endif
  @if (session('success'))
  <div class="alert alert-success" role="alert">{{ session('success') }}</div>
  @endif
  <form class="form-horizontal" method="POST" action="{{ route('reset_password.submit') }}">
    {{ csrf_field() }}

    <div class="form-group">
      <label for="old_password" class="col-md-4 control-label">Original Password</label>

      <div class="col-md-6">
        <input id="old_password" type="password" class="form-control" name="old_password" required autofocus>
      </div>
    </div>

    <div class="form-group">
      <label for="new_password" class="col-md-4 control-label">New Password</label>

      <div class="col-md-6">
        <input id="new_password" type="password" class="form-control" name="new_password" required>
      </div>
    </div>

    <div class="form-group">
      <label for="new_password_verify" class="col-md-4 control-label">Confirm New Password</label>

      <div class="col-md-6">
        <input id="new_password_verify" type="password" class="form-control" name="new_password_verify" required>
      </div>
    </div>

    <div class="form-group">
      <div class="col-md-8 col-md-offset-4">
        <input type="submit" class="btn btn-primary" />
        <input type="reset" class="btn btn-danger" />
      </div>
    </div>
  </form>

  @push('scripts')
  <script src="{{ asset('js/app.js') }}"></script>
  <script>
    var password = document.getElementById("new_password")
      , confirm_password = document.getElementById("new_password_verify");

    function validatePassword() {
      if (password.value != confirm_password.value) {
        confirm_password.setCustomValidity("Passwords Don't Match");
      } else {
        confirm_password.setCustomValidity('');
      }
    }

    password.onchange = validatePassword;
    confirm_password.onkeyup = validatePassword;
  </script>
  @endpush
</div>
@endsection