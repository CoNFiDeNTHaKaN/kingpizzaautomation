@extends('emails.template')

@section('content')
  <h1>Hi {{$v->studentname ?? ''}}</h1>
  <p>Assessment is underway, please <a href="https://curiosityapproach.worldsecuresystems.com/members-portal/overview">log in to see feedback</a> from your assessor and take any actions indicated/answer any queries.
  </p>
  <p>Thank you</p>
@endsection
