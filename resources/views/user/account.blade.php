@extends('layouts.account')

@section('pageTitle', 'My account')

@section('content')
    @include('user.sidebar')
    <div class="content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 mt-5">
                    <h3>Good to see you back at Eat Kebab</h3>
                    <p>From here you can manage existing orders, update your account information and more.</p>
                    <p>Use the sidebar on the left to manage your account.</p>
                <p>Or <a href="{{route('home')}}">click here to find something to eat.</a></p>
                </div>
            </div>
        </div>
    </div>
@endsection

