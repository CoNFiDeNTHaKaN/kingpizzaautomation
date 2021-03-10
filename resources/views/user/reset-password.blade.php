@extends('layouts.main')


@section('pageTitle', 'Reset your password')

@section('content')
  <div class="container">
  <div class="row mt-5">
  <div class="col-12 mt-5">

      <h2>Reset your password</h2>
      {!!session('message') ?? ''!!}
      <p>Please enter your email to reset your password.</p>
      <form method="post" enctype="multipart/form-data" action="{{ route('password.email') }}">
        {{ csrf_field() }}
        <div class="form-group">
          <input class="form-control" type="email" name="email" placeholder="E-mail">
        </div>
        <div class="formitem flex flex--align-right">
          <input type="hidden" name="url" value="{{ request()->get('redirect_to') }}">
          <input type="submit" value="Reset" class="btn btn_1">
        </div>

      </form>
      </div>

    </div>
  </div>
@endsection
