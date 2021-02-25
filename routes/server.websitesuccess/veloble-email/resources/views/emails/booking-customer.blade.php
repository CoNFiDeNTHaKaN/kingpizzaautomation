@extends('emails.template')

@section('content')
  <h1>Your booking has been submitted</h1>
  <p>Great news {{ $v->customername }}, your booking of {{ $v->bikename }} has been sent to the owner {{ $v->ownername }}.</p>
  <p>To get in contact with {{ $v->ownername }} you can email {{ $v->owneremail }}</p>
  <p><b>Selected dates</b> {{ $v->hiredates }}</p>
  <p><b>Price paid</b> £{{ $v->price }}</p>
  <p><b><a href="https://www.veloble.com/my-account">Log in to your account to see the full details</a></b></p>
@endsection
