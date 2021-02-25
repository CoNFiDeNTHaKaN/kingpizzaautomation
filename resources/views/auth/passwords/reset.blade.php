@extends('templates.slim')

@section('pageTitle', 'Reset your password')

@push('contentClasses', 'kebab-bg')

@section('content')
  <div class="wrapper flex flex--align-center">

    <div class="modal modal--narrow t-center">
      <h4>Login to Eat Kebab Online</h4>
      <form method="POST" action="{{ route('password.update') }}">
        {{ csrf_field() }}
        <div class="formitem">
          <label>Email</label>
          <input type="email" name="email" value="{{ $email ?? '' }}">
        </div>
        <div class="formitem">
          <label>Password</label>
          <input type="password" name="password">
        </div>
        <div class="formitem">
          <label>Confirm password</label>
          <input type="password" name="password_confirmation">
        </div>
        <div class="formitem flex flex--align-right">
          <input type="hidden" name="token" value="{{ $token }}">
          <input type="submit" value="Login" class="button  button--green">
        </div>

      </form>
      {{-- <hr label="OR">
      <a href="/fbl" class="button button--fb">Continue with Facebook</a> --}}
    </div>

  </div>
@endsection
