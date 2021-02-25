@extends('mail.template')

@section('content')
    <p><b>Order Number</b> : {{ $order->id }}</p>
    <p><b>Name of Customer</b> : {{ $user->first_name . " " . $user->last_name }}</p>
    @if ($order->collection)
        @if (strtotime($order->desired_time) < time())
            <p><b>Requested collection time</b> : ASAP</p>
        @else
            <p><b>Requested collection time</b> : {{ date( 'G:i',strtotime($order->desired_time)) }}</p>
        @endif
    @else
        @if (strtotime($order->desired_time) < time())
            <p><b>Requested delivery time</b> : ASAP</p>
        @else
            <p><b>Requested delivery time</b> : {{ date( 'G:i',strtotime($order->desired_time)) }}</p>
        @endif
        <p><b>Delivery Address</b> : </p>
        <p>{{ $order->delivery_line1 }}</p>
        @if (isset($order->delivery_line2))
            <p>{{ $order->delivery_line2 }}</p>
        @endif
        <p>{{ $order->delivery_city }}</p>
        @if (isset($order->delivery_county))
            <p>{{ $order->delivery_county }}</p>
        @endif
        <p>{{ $order->delivery_postcode }}</p>
    @endif
    <p><b>Order details</b> : </p>
    @foreach ($basket->contents as $orderItem)
    <div class="restaurant-order__item">
        <span class="restaurant-order__item__name"><b>{{$orderItem['name']}}</b></span>
        <span class="restaurant-order__item__size">{{$orderItem['sizes']}}</span>
        <span class="restaurant-order__item__price">£ @money_format($orderItem['total_price'])</span>
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
                        <span class="restaurant-order__item__options__price">(+£ @money_format( $optionValue['additional_charge']))</span>

                    @endif
                    </div>
                @endif
                @endforeach
            @endif
        @endforeach
    </div>
    @endforeach
    
@endsection
