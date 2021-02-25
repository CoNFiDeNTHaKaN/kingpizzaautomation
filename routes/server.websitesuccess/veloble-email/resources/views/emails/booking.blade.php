@extends('emails.template')

@section('content')
  <h1>Your bike has been booked</h1>
  <p>Great news {{ $v->hirername }}, {{ $v->hireename }} has booked {{ $v->bikename }}</p>
  <p><b>Selected dates</b> {{ $v->hiredates }}</p>
  <p><b>Price paid</b> £{{ $v->price }}</p>
  <p><b><a href="https://www.veloble.com/my-account">Log in to your account to see the full details</a></b></p>
@endsection
