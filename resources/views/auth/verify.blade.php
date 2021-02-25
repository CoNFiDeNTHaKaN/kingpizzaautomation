@extends('layouts.main')

@section('content')
@if (session('resent'))
<div class="alert alert-success" role="alert">
    {{ __('A fresh verification link has been sent to your email address.') }}
</div>
@endif
<div class="container">
<div class="card"  style="min-height:200px; text-align:center; margin-top:100px;">
    <div class="card-body">
      <h5 class="card-title">{{ __('Verify Your Email Address') }}</h5>
      <p class="card-text">  {{ __('Before proceeding, please check your email for a verification link.') }}<br>
        {{ __('If you did not receive the email') }},<br><br></p>
        <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
            @csrf
            <button type="submit" class="btn btn-primary">{{ __('Click Here to Request Another') }}</button>.
        </form>
    </div>
  </div>
</div>
@endsection
