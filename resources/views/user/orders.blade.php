@extends('layouts.main')

@section('pageTitle', 'My orders')

@section('content')
    <div class="content-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-12 mt-5">
                  <h3>Your order history</h3>
                  @if(count($orders)>0)
                    @foreach($orders as $order)
                      <div class="row mt-3" style="font-weight:bolder; font-size:16px;" >
                        <div class="col-12" style="margin:10px 0px 10px 0px;">
                          Placed at:{{$order->created_at}} | Payment Method: {{$order->payment_type}} 
                          @if($order->payment_type=="card")
                            @if($order->paid==0)
                             | <b style="color:red">Payment Failed</b>
                             @else
                             | <b style="color:green">Successfully Paid</b>
                             @endif
                          @endif
                           | Price: &pound;{{$order->basket->total}}<br>
                           <a href="{{route('restaurants.thanks',$order->id)}}">View status</a>
                        </div>
                      </div>
                      <div class="row" style="box-shadow: 5px 5px 18px #888888;">
                        <div class="col-8">
                          @foreach ($order->basket->contents as $orderItem)
                            <b>{{$orderItem['name']}}</b>
                            {{$orderItem['sizes']}}
                            <span>&pound; @money_format($orderItem['total_price'])</span>
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
                                    <div class="pl-2">
                                      <i class="fas fa-check" style="color:green;"></i>
                                      {{$optionValue['name']}}
                                      @if ($optionValue['additional_charge'] > 0)
                                        (&plus;&pound;@money_format($optionValue['additional_charge']))
                                      @endif
                                    </div>
                                  @endif
                                @endforeach
                              @endif
                          @endforeach
                          @endif
                        @endforeach
                        </div>
                        <div class="col-4">
                          @if($order->collection==0)
                          <b>Delivery Address</b><br>
                          {{$order->delivery_line1}}<br>
                          {{$order->delivery_line2}}<br>
                          {{$order->delivery_city}}<br>
                          {{$order->delivery_postcode}}
                        
                          @endif
                        </div>
                      </div>
                    @endforeach
                  @else
                  You don't have any orders.
                  @endif
                </div>
            </div>
        </div>
    </div>
  <div class="mt-5"></div>
@endsection


