@extends('layouts.main')

@section('pageTitle', 'Page not found')



@section('content')

<main class="bg_gray pt-5">
  <div id="error_page">
    <div class="container mt-5">
      <div class="row justify-content-center text-center">
        <div class="col-xl-7 col-lg-9">
          <figure><img src="{{asset('img/404.svg')}}" alt="" class="img-fluid" width="550" height="234"></figure>
          <h1>Page not found</h1>
          <h4>This could be an old link or this page may have moved.</h4>
          <p><a href="{{route('home')}}">Click here to go home</a></p>
        </div>
      </div>
      <!-- /row -->
    </div>
    <!-- /container -->
  </div>
  <!-- /error -->		
</main>

@endsection
