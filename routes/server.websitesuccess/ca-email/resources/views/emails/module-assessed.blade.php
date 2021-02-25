@extends('emails.template')

@section('content')
  <h1>Congratulations {{$v->studentname ?? ''}}</h1>
  <p>Your assessment is now complete, feedback will be submitted to The Curiosity Approach&reg; HQ for final accreditation confirmation. Please check your emails and get in touch if you haven't heard from us within the next 10 working days.
  </p>
@endsection
