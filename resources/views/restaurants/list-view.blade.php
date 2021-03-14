@extends('layouts.main')


@section('pageTitle', 'Order food now')

@push('headScripts')
<link href="{{asset('css/listing.css')}}" rel="stylesheet">
<link href="{{asset('css/carousel.css')}}" rel="stylesheet">

@endpush

@section('content')


<main>
  <div class="page_header element_to_stick">
      <div class="container">
          <div class="row">
              <div class="col-xl-8 col-lg-7 col-md-7 d-none d-md-block">
                  <h1>{{count($restaurants)}} restaurants in {{$_GET['postcode']}}</h1>
                  <a href="{{route('restaurants.clearLocation')}}">Change address</a>
              </div>
              <!--<div class="col-xl-4 col-lg-5 col-md-5">
                  <div class="search_bar_list">
                      <input type="text" class="form-control" placeholder="Dishes, restaurants or cuisines">
                      <button type="submit"><i class="icon_search"></i></button>
                  </div> 
              </div>-->
          </div>
          <!-- /row -->
      </div>
  </div>
  <!-- /page_header -->
  <!--
  <div class="filters_full clearfix add_bottom_15">
      <div class="container">
          <div class="type_delivery">
        <ul class="clearfix">
          <li>
                <label class="container_radio">All
                    <input type="radio" name="type_d" value="all" id="all" checked data-filter="*" class="selected">
                    <span class="checkmark"></span>
                </label>
            </li>
            <li>
                <label class="container_radio">Delivery
                    <input type="radio" name="type_d" value="delivery" id="delivery" data-filter=".delivery">
                    <span class="checkmark"></span>
                </label>
            </li>
            <li>
                <label class="container_radio">Takeaway
                    <input type="radio" name="type_d" value="takeway" id="takeaway" data-filter=".takeaway">
                    <span class="checkmark"></span>
                </label>
            </li>
        </ul>
    </div>
    
          <a class="btn_map mobile btn_filters" data-toggle="collapse" href="grid-listing-masonry.html#collapseMap"><i class="icon_pin_alt"></i></a>
          <a href="grid-listing-masonry.html#collapseFilters" data-toggle="collapse" class="btn_filters"><i class="icon_adjust-vert"></i><span>Filters</span></a>
      </div>
  </div>
  
  <div class="collapse" id="collapseMap">
  <div id="map" class="map"></div>
</div>


  <div class="collapse filters_2" id="collapseFilters">
      <div class="container margin_30_20">
          <div class="row">
              <div class="col-md-4">
                  <div class="filter_type">
                      <h6>Categories</h6>
                      <ul>
                          <li>
                              <label class="container_check">Pizza - Italian <small>12</small>
                                  <input type="checkbox">
                                  <span class="checkmark"></span>
                              </label>
                          </li>
                          <li>
                              <label class="container_check">Japanese - Sushi <small>24</small>
                                  <input type="checkbox">
                                  <span class="checkmark"></span>
                              </label>
                          </li>
                          <li>
                              <label class="container_check">Burghers <small>23</small>
                                  <input type="checkbox">
                                  <span class="checkmark"></span>
                              </label>
                          </li>
                          <li>
                              <label class="container_check">Vegetarian <small>11</small>
                                  <input type="checkbox">
                                  <span class="checkmark"></span>
                              </label>
                          </li>
                      </ul>
                  </div>
              </div>
              <div class="col-md-4">
                  <div class="filter_type">
                      <h6>Rating</h6>
                      <ul>
                          <li>
                              <label class="container_check">Superb 9+ <small>06</small>
                                  <input type="checkbox">
                                  <span class="checkmark"></span>
                              </label>
                          </li>
                          <li>
                              <label class="container_check">Very Good 8+ <small>12</small>
                                  <input type="checkbox">
                                  <span class="checkmark"></span>
                              </label>
                          </li>
                          <li>
                              <label class="container_check">Good 7+ <small>17</small>
                                  <input type="checkbox">
                                  <span class="checkmark"></span>
                              </label>
                          </li>
                          <li>
                              <label class="container_check">Pleasant 6+ <small>43</small>
                                  <input type="checkbox">
                                  <span class="checkmark"></span>
                              </label>
                          </li>
                      </ul>
                  </div>
              </div>
              <div class="col-md-4">
                  <div class="filter_type">
                      <h6>Distance</h6>
                      <div class="distance"> Radius around selected destination <span></span> km</div>
                      <div class="mb-3
                      "><input type="range" min="10" max="100" step="10" value="30" data-orientation="horizontal"></div>
                  </div>
              </div>
          </div>
      </div>
  </div>
-->
  <!-- /filters -->
  <div class="container margin_30_20">
    <div class="row isotope-wrapper">
      @if(count($restaurants)>0)
      @foreach ($restaurants as $restaurant)
          
      <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 isotope-item {{$restaurant->delivery_now ? 'delivery':''}} {{$restaurant->order_now ? 'takeaway':''}}">
        <div class="strip">
            <figure>
				<!-- yeni eklediklerim -->
				<link rel="stylesheet" href="css/carousel.css">
				<div id="carouselFade" class="carousel slide carousel-fade" data-ride="carousel">
					<div class="carousel-inner">
                        @if(count($restaurant->cover)>0)
                        @foreach($restaurant->cover as $cover)
                        <div class="carousel-item {{$loop->first ? 'active' : ''}}">
						  <img src="img/lazy-placeholder.png" class="img-fluid lazy" data-src="{{$cover}}">
						</div>
                        @endforeach
                        @else
                            <img src="img/lazy-placeholder.png" class="img-fluid lazy" data-src="{{$cover}}">
                        @endif
						<!--<div class="carousel-item active">
						  <img src="img/shopImg/kebab1.jpg" class="d-block w-100" alt="...">
						</div>
						<div class="carousel-item">
						  <img src="img/shopImg/kebab2.jpg" class="d-block w-100" alt="...">
						</div>
						<div class="carousel-item">
						  <img src="img/shopImg/kebab3.jpg" class="d-block w-100" alt="...">
						</div>
						<div class="carousel-item">
						  <img src="img/shopImg/kebab4.jpg" class="d-block w-100" alt="...">
						</div>
						<div class="carousel-item">
						  <img src="img/shopImg/kebab5.jpg" class="d-block w-100" alt="...">
						</div>
						-->
					</div>
				</div>
				<!-- yeni son-->
                
                <a href="{{route('restaurants.goto', ['slug' => $restaurant->slug]) }}" class="strip_info">
                  <!--  <small>
                   
                    </small> -->
                    <div class="item_title">
                    <h3>{{$restaurant->name}}</h3>
                    <small>{{$restaurant->address_line_1}}, {{$restaurant->address_line_2}}, {{$restaurant->address_postcode}}</small><br>

					</div>
                </a>
            </figure>
            <ul>
                <li><span class="take {{$restaurant->order_now ? 'yes':'no'}}">Takeaway</span> <span class="deliv {{$restaurant->delivery_now ? 'yes':'no'}}">Delivery</span></li>
                <li>
                    <div class="score"><strong>{{$restaurant->rating}}</strong></div>
                </li>
            </ul>
            
                <div class="row">
                    <div class="col-12" style="z-index:99999999; background-color:white;">
                        @php
                        $w = date("w");
                        @endphp
                        <table style="width:100%; font-weight:bold;">
                            <tr>
                                <td style="width:45%">Opening Hours</td>
                                <td style="width:5%">:</td>
                                <td style="width:50%">{{$restaurant->formatted_hours($restaurant->opening_hours[$w][0])}} - {{$restaurant->formatted_hours($restaurant->opening_hours[$w][1])}}</td>
                            </tr>
                            <tr>
                                <td style="width:45">Take-Away Hours</td>
                                <td style="width:5%">:</td>
                                <td style="width:50%">{{$restaurant->formatted_hours($restaurant->order_hours[$w][0])}} - {{$restaurant->formatted_hours($restaurant->order_hours[$w][1])}}</td>
                            </tr>
                            <tr>
                                <td style="width:45%">Delivery Hours</td>
                                <td style="width:5%">:</td>
                                <td style="width:50%">{{$restaurant->formatted_hours($restaurant->delivery_hours[$w][0])}} - {{$restaurant->formatted_hours($restaurant->delivery_hours[$w][1])}}</td>
                            </tr>
                        </table>
                         
                    </div>
                    
                </div>
        
        </div>
      </div>

      @endforeach
      @else
      No Restaurant Found
      @endif

    </div>
  </div>


@endsection

@push('footerScripts')
<script src="{{asset('js/sticky_sidebar.min.js')}}"></script>
<script src="{{asset('js/specific_listing.js')}}"></script>
<script src="{{asset('js/isotope.min.js')}}"></script>
<script>
$('.popover-dismiss').popover({
  trigger: 'focus'
})
$(function () {
  $('[data-toggle="popover"]').popover()
})
$(window).on("load",function(){
  var $container = $('.isotope-wrapper');
  $container.isotope({ itemSelector: '.isotope-item', layoutMode: 'masonry' });
});
$('.type_delivery').on( 'click', 'input', 'change', function(){
  var selector = $(this).attr('data-filter');
  $('.isotope-wrapper').isotope({ filter: selector });
});
</script>

<!-- Map -->
<script src="{{asset('js/main_map_scripts.js')}}"></script>

@endpush