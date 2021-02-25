@extends('emails.template')

@section('content')
  <h1>Thanks {{$v->studentname ?? ''}}</h1>
  <p> Thank you for uploading your module(s), your assessment will be undertaken in due course. Please check your emails for any further feedback. </p>
@endsection
