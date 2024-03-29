@section('pageTitle', 'Order freshly cooked local kebab')

@section('pageCss')
<link href="{{asset('css/home.css')}}" rel="stylesheet">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
<link href="{{asset('css/homevideo.css')}}" rel="stylesheet">
@endsection

@extends('layouts.main')


@section('content')
<div class="hero_single version_1">

  <!-- yeni ekledim -->
  <button class="btn_1 gradient btnd">Search</button>
  <!-- *** -->
  <div class="opacity-mask">
      <div class="container">
          <div class="row justify-content-lg-start">
              <div class="col-xl-6 col-lg-8">
                @if ($errors->any())
				  <!-- yeni düzenledim -->
                  <div class="searchError">
				  <!-- *** -->
                    <ul>
                      @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                      @endforeach
                    </ul>
                  </div>
                @endif
                  @if (Session::has('success'))
                    <div class="alert alert-success">
                      <ul>
                        <li>{{ Session::get('success') }}</li>
                      </ul>
                    </div>
                  @endif
                  <h2>Order your kebab right now</h2>
                  <h5>from the comfort of your own home, or pub!</h5>
                  <form method="get" action="{{route('restaurants.list')}}">
                      <div class="row no-gutters custom-search-input">
                          <div class="col-lg-9">
                              <div class="form-group">
                                  <input class="form-control no_border_r" name="postcode" type="text" value="{{ Cookie::get('eko_postcode') }}" id="autocomplete" placeholder="PO21 ---">
                              </div>
                          </div>
                          <div class="col-lg-3">
                              <button class="btn_1 gradient" type="submit">Search</button>
                          </div>
                      </div>
                      <!-- /row -->
                      <div class="search_trends">
                          <h5>Trending:</h5>
                          <ul>
                              <li><a href="#0">Kebab</a></li>
                              
                          </ul>
                      </div>
                  </form>
              </div>
			  
          </div>
          <!-- /row -->
		  <div class="row">
			 <div class="col-sm-12 col-lg-4">
				<img src="{{asset('video/teslimat.svg')}}" alt="" class="orderStep">
			 </div>
		  </div>
      </div>
  </div>
  <div class="background-video  d-md-block">
					<video muted loop playsinline autoplay id="homepage-video">
					   <source src="{{asset('video/bg1.mp4')}}" type="video/mp4">
					</video>
			</div>
			<div class="social">
	<ul class="social-btn">
		<li class="list-heading">Follow Us </li>
		<li><a href="#" target="_blank"><i class="fab fa-facebook-f" aria-hidden="true"></i></a></li>
		<li><a href="#" target="_blank"><i class="fab fa-twitter" aria-hidden="true"></i></a></li>
	    <li><a href="#" target="_blank"><i class="fab fa-instagram" aria-hidden="true"></i></a></li>
	    <li><a href="#" target="_blank"><i class="fab fa-youtube" aria-hidden="true"></i></a></li>
		<li><a href="#" target="_blank"><i class="fab fa-tripadvisor" aria-hidden="true"></i></a></li>
	</ul>
</div>
</div>


    <section class="favourites">
      <div class="container">
        <div class="row text-center">
          <div class="col-12">
            <h2>browse our favourites</h2>
            <p>here is a shortlist of our favourites from our restaurants</p>
          </div>
          <div class="col-6 col-sm-6 col-md-4">
            <a href="{{ route('restaurants.list', ['favourites' => 'kebabs']) }}">
              <img class="img-round" src="{{asset('/images/build/favourites/kebabs.jpg')}}" style="width:100%">
              <span>kebabs</span>
            </a>
          </div>
          <div class="col-6 col-sm-6 col-md-4">
            <a href="{{ route('restaurants.list', ['favourites' => 'burgers']) }}">
            <img class="img-round" src="{{asset('/images/build/favourites/burgers.jpg')}}" style="width:100%">
              <span>burgers</span>
            </a>
          </div>
          <div class="col-6 col-sm-6 col-md-4">
            <a href="{{ route('restaurants.list', ['favourites' => 'pizzas']) }}">
              <img class="img-round" src="{{asset('/images/build/favourites/pizzas.jpg')}}" style="width:100%">
              <span>pizzas</span>
            </a>
          </div>
          <div class="col-6 col-sm-6 col-md-4">
            <a href="{{ route('restaurants.list', ['favourites' => 'wraps']) }}">
              <img class="img-round" src="{{asset('/images/build/favourites/wraps.jpg')}}" style="width:100%">
              <span>wraps</span>
            </a>
          </div>
          <div class="col-6 col-sm-6 col-md-4">
            <a href="{{ route('restaurants.list', ['favourites' => 'pasta']) }}">
              <img class="img-round" src="{{asset('/images/build/favourites/pasta.jpg')}}" style="width:100%">
              <span>pasta</span>
            </a>
          </div>
          <div class="col-6 col-sm-6 col-md-4">
            <a href="{{ route('restaurants.list', ['favourites' => 'steak-ribs']) }}">
              <img class="img-round" src="{{asset('/images/build/favourites/steak-ribs.jpg')}}" style="width:100%">
              <span>steak & ribs</span>
            </a>
          </div>
        </div>
      </div>
    </section>

@endsection

@push('footerScripts')
<!-- yeni ekledim -->
<script>
	$(document).ready(function(){
	  $(".btnd").click(function(){
		$(".opacity-mask").addClass("show");
		$(".btnd").addClass("hide");
	  });
	});
</script>
<!-- **** -->
@endpush