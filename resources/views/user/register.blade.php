@extends('layouts.slim')

@push('bodyIds', 'register_bg')

@section('pageTitle', 'Register for Eat Kebab Online')

@section('content')

<div id="register">
  <aside>
    <figure>
    <a href="{{route('home')}}"><img src="{{asset('img/logo_sticky.svg')}}" width="140" height="35" alt=""></a>
    </figure>
    <div id="comingsoon" class="text-center" style="display:none; color:red; font-size:20px;"></div>
    @if ($errors->any())
    <div class="alert alert-danger" style="color:red; font-size:20px;">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <div class="access_social">
        <!-- <a href="#0" onclick="comingSoon()" class="social_bt facebook">Register with Facebook</a>
        <a href="#0" onclick="comingSoon()" class="social_bt google">Register with Google</a> -->
      </div>
          <div class="divider"><span>Or</span></div>
          @if (request()->has('redirectTo'))
          <form method="post" autocomplete="off" action="{{ route('user.registerSubmit', ['redirectTo' => request()->redirectTo]) }}">
           @else

          <form method="post"autocomplete="off" action="{{ route('user.registerSubmit') }}">
           @endif
      {{ csrf_field() }}
      <div class="form-group">
        <input class="form-control" type="text" value="{{ old('first_name') }}" name="first_name" placeholder="First name">
        <i class="icon_pencil-edit"></i>
      </div>
      <div class="form-group">
        <input class="form-control" type="text" value="{{ old('last_name') }}" name="last_name" placeholder="Last name">
        <i class="icon_pencil-edit"></i>
      </div>
      <div class="form-group">
        <input class="form-control" type="email" value="{{ old('email') }}" name="email" placeholder="Email">
        <i class="icon_mail_alt"></i>
      </div>
      <div class="form-group">
        <input class="form-control" type="password" name="password" id="password" placeholder="Password">
        <i class="icon_lock_alt"></i>
      </div>
      <!--
      <div class="form-group">
		    <div class="text-center" style="width:100%;">{!!$captcha!!}</div>
        <input type="text" class="form-control mt-2" name="captcha">
        
      </div>
      -->
      <button type="submit" class="btn_1 gradient full-width">Register Now!</button>
      <div class="text-center mt-2"><small>Already have an acccount? <strong><a href="{{ route('user.login') }}">Login</a></strong></small></div>
    </form>
  
    <div class="copy">© 2020 Eat Kebab UK  </div>
  </aside>
</div>

@endsection
@section('postcontent')
    <script>/*
      function comingSoon(){
        document.getElementById('comingsoon').style.display="block";
        document.getElementById('comingsoon').innerHTML='Coming Soon';
      }*/
    </script>
@endsection
@section('pageCss')
<link href="{{asset('css/order-sign_up.css')}}" rel="stylesheet">
@endsection