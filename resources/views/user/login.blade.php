@extends('layouts.slim')

@push('bodyIds', 'register_bg')

@section('pageTitle', 'Login to Eat Kebab Online')

@section('content')

<div id="register">
  <aside>
    <figure>
    <a href="{{route('home')}}"><img src="{{asset('video/logo_sticky.svg')}}"  height="35" alt=""></a>
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
        <a href="#0" onclick="comingSoon()" class="social_bt facebook">Login with Facebook</a>
        <a href="#0" onclick="comingSoon()" class="social_bt google">Login with Google</a>
      </div>
          <div class="divider"><span>Or</span></div>
    <form action="{{ route('user.loginSubmit') }}" method="POST" autocomplete="off">
      {{ csrf_field() }}
      <div class="form-group">
        <input class="form-control" type="email" name="email" placeholder="Email">
        <i class="icon_mail_alt"></i>
      </div>
      <div class="form-group">
        <input class="form-control" type="password" name="password" id="password" placeholder="Password">
        <i class="icon_lock_alt"></i>
        <label class="container_check mt-1"><input type="checkbox" onclick="showPassword()">Show Password <span class="checkmark"></span></label>
      </div>
      <div class="clearfix add_bottom_15">
        <div class="checkboxes float-left">
          <label class="container_check">Remember me
            <input type="checkbox">
            <span class="checkmark"></span>
          </label>
        </div>
        <div class="float-right"><a id="forgot" href="{{ route('user.resetPassword') }}">Forgot Password?</a></div>
      </div>
      <button type="submit" class="btn_1 gradient full-width">Login Now!</button>
      <div class="text-center mt-2"><small>Don't have an acccount? <strong><a href="{{ route('user.register',['redirectTo' => request()->url()]) }}">Sign Up</a></strong></small></div>
      <input type="hidden" name="url" value="{{ request()->get('redirect_to') }}">
    </form>
  
    <div class="copy">© 2020 Eat Kebab UK  </div>
  </aside>
</div>

@endsection

@section('pageCss')
<link href="{{asset('css/order-sign_up.css')}}" rel="stylesheet">
@endsection

@section('postcontent')
    <script>
      function comingSoon(){
        document.getElementById('comingsoon').style.display="block";
        document.getElementById('comingsoon').innerHTML='Coming Soon';
      }

      function showPassword() {
        var x = document.getElementById("password");
        if (x.type === "password") {
          x.type = "text";
        } else {
          x.type = "password";
        }
      }
    </script>
@endsection