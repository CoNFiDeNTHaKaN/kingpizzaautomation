@extends('templates.manager')

@section('content')
  <div class="wrapper">
    <h1>Eat Kebab Online live orders</h1>
    <h2>Welcome back, {{ $user->restaurant->name ?? "" }}</h2>
    <p></p>
    <div class="row t-center">
        <div class="col-12 col-sm-6 col-md-12"><a href="{{ route('manager.orders') }}">Current orders</a></div>
        <div class="col-12 col-sm-6 col-md-4"><a href="{{ route('manager.order-history') }}">Order history</a></div>
        <div class="col-12 col-sm-6 col-md-4"><a href="{{ route('manager.edit-menu') }}">Edit menu</a></div>
        <div class="col-12 col-sm-6 col-md-4"><a href="{{ route('manager.edit-info') }}">Edit restaurant information</a></div>
    </div>
  </div>
@endsection
