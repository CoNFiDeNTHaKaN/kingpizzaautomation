@section('pageTitle', 'Order freshly cooked local kebab')

@section('pageCss')
<link href="{{asset('css/home.css" rel="stylesheet')}}">
@endsection

@extends('layouts.main')


@section('content')

<div class="hero_single version_1">
  <div class="opacity-mask">
      <div class="container">
          <div class="row justify-content-lg-start justify-content-md-center">
              <div class="col-xl-6 col-lg-8">
                @if ($errors->any())
                  <div style="background-color:rgb(255, 161, 161); color:black; border-radius:10px;">
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
                          <div class="col-lg-10">
                              <div class="form-group">
                                  <input class="form-control no_border_r" name="postcode" type="text" value="{{ Cookie::get('eko_postcode') }}" id="autocomplete" placeholder="PO21 ---">
                              </div>
                          </div>
                          <div class="col-lg-2">
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
      </div>
  </div>
  <div class="wave hero"></div>
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
