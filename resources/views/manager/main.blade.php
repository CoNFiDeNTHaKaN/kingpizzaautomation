@extends('templates.manager')

@section('pageTitle', 'Restaurant managers Home')

@section('content')
  <div class="wrapper">
    <h1>Eat Kebab Online business manager</h1>
    <h2>Welcome back, {{ $user->restaurant->name ?? "" }}</h2>
    <p></p>
    <div class="row t-center manager-home-buttons">
        <div class="col-12 col-sm-6 col-md-12 manager-home-buttons__orders"><a href="{{ route('manager.orders') }}">Current orders</a></div>
        <div class="col-12 col-sm-6 col-md-4 manager-home-buttons__orderhistory"><a href="{{ route('manager.orderHistory') }}">Order history</a></div>
        <div class="col-12 col-sm-6 col-md-4 manager-home-buttons__editmenu"><a href="{{ route('manager.editMenu') }}">Edit menu</a></div>
        <div class="col-12 col-sm-6 col-md-4 manager-home-buttons__editinfo"><a href="{{ route('manager.editInfo') }}">Edit restaurant information</a></div>
    </div>
  </div>
@endsection
