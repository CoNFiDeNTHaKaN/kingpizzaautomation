@extends('layouts.main')



@section('content')
  <div class="container mt-5 pt-3">
	<div class="row">
	<div class="col-12 text-center">
	<h4>Review and complete your order</h4>
	</div>
	</div>
    <div class="row">
      @foreach ($basket->contents as $orderItem)
          <div class="col-xl-4 col-md-6 col-sm-12 mt-2" style="margin:0 auto;">
			<div style="box-shadow: 5px 5px 18px #888888; margin:5px;">
            <span class="restaurant-order__item__name"><b>{{$orderItem['name']}}</b></span>
            <span class="restaurant-order__item__size">{{$orderItem['sizes']}}</span>
            <span class="restaurant-order__item__price">&pound; @money_format($orderItem['total_price'])</span>
            @if(array_key_exists('options',$orderItem))
            @foreach ($orderItem['options'] as $option)
              @php
                  $optionHasValues = false;
              @endphp
              @foreach ($option['option_values'] as $optionValue)
                @if($optionValue['selected'] == 'true')
                  @php
                      $optionHasValues = true;
                  @endphp
                @endif
              @endforeach
              @if ($optionHasValues)
                  @foreach ($option['option_values'] as $optionValue)
                    @if($optionValue['selected'] == 'true')
                      <div class="restaurant-order__item__options">
                        <i class="fas fa-check t-green"></i>
                        <span class="restaurant-order__item__options__name">{{$optionValue['name']}}</span>
                        @if ($optionValue['additional_charge'] > 0)
                          <span class="restaurant-order__item__options__price">(&plus;&pound; @money_format($optionValue['additional_charge']) )</span>

                        @endif
                      </div>
                    @endif
                  @endforeach
              @endif
            @endforeach
            @endif
			</div>
          </div>
      @endforeach
    </div>
    <div class="row mt-3 text-center">
        <div class="col-12">

          <div class="flex flex--spread">
            <a href="{{ route('restaurants.goto', ['slug'=>$basket->restaurant->slug]) }}" class="btn btn_1">Back to edit order</a>  
            <a href="{{ route('restaurants.confirm') }}" class="btn btn_1">Order now</a>
          </div>
        </div>

    </div>

  </div>
@endsection
