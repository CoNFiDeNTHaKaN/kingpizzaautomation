@extends('templates.slim')

@push('contentClasses', 'kebab-bg')

@section('pageTitle', 'Reset your password')

@section('content')
  <div class="wrapper flex flex--align-center">

    <div class="modal modal--narrow t-center">
      <h2>Reset your password</h2>
      <form method="post" enctype="multipart/form-data" action="{{ route('password.email') }}">
        {{ csrf_field() }}
        <div class="formitem">
          <label>Email</label>
          <input type="email" name="email">
        </div>
        <div class="formitem flex flex--align-right">
          <input type="hidden" name="url" value="{{ request()->get('redirect_to') }}">
          <input type="submit" value="Reset" class="button button--green">
        </div>

      </form>
    </div>

  </div>
@endsection
