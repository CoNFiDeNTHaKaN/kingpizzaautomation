@extends('layouts.main')

@section('pageTitle', 'Thanks for your order')

@push('headScripts')
  <link href="{{asset('css/order-sign_up.css')}}" rel="stylesheet">

@endpush

@section('content')

<main class="bg_gray pt-5">
		
  <div class="container margin_60_40">
      <div class="row justify-content-center">
          <div class="col-lg-4">
            <div class="box_order_form">
                  <div class="head text-center">
                      <h3>Your Order Received Us</h3>
                  </div>
                  <!-- /head -->
                  <div class="main">
                    <div id="confirm">
              <div class="icon icon--order-success svg add_bottom_15">
                <svg xmlns="http://www.w3.org/2000/svg" width="72" height="72">
                  <g fill="none" stroke="#8EC343" stroke-width="2">
                    <circle cx="36" cy="36" r="35" style="stroke-dasharray:240px, 240px; stroke-dashoffset: 480px;"></circle>
                    <path d="M17.417,37.778l9.93,9.909l25.444-25.393" style="stroke-dasharray:50px, 50px; stroke-dashoffset: 0px;"></path>
                  </g>
                </svg>
              </div>
              <h3>Great news! Your order is on its way to {{ $order->restaurant->name }}.</h3>
			  <h4><b style="color:red;"> !!! Warning !!! <br> Please Check Your Email </b></h4>
              <p>Once your order has been accepted in store, you will receive an email to confirm your delivery time. Live updates will also appear here and will refresh automatically.</p>
              <p><b>Order status</b> : {{ str_replace('_' , ' ' , $order->status->name) }}</p>
        @if ($order->collection)
          @if (strtotime($order->desired_time) < time())
            <p><b>Requested collection time</b> : ASAP</p>
          @else
            <p><b>Requested collection time</b> : {{ date( 'G:i',strtotime($order->desired_time)) }}</p>
          @endif
          <p><b>Ready for collection</b> : {{ $order->predicted_time ?? 'Awaiting confirmation' }}</p>
        @else
          @if (strtotime($order->desired_time) < time())
            <p><b>Requested delivery time</b> : ASAP</p>
          @else
            <p><b>Requested delivery time</b> : {{ date( 'G:i',strtotime($order->desired_time)) }}</p>
          @endif
          <p><b>Due for delivery</b> : {{ $order->predicted_time ? date( 'G:i',strtotime($order->predicted_time)) : 'Awaiting confirmation' }}</p>
        @endif

        <br>
        <p><span class="t-12x">If you have any questions about your order or if you didn't get confirmation email or need to speak to the restaurant it's best to speak to them - call <b>{{ $order->restaurant->name }}</b> on&nbsp; <br><b>{{ $order->restaurant->contact_number }}</b></span></p>
            </div>
                  </div>
              </div>
              <!-- /box_booking -->
          </div>
          <!-- /col -->
      </div>
      <!-- /row -->
  </div>
  <!-- /container -->
  
</main>

@endsection

@push('footerScripts')
<script>
 window.setInterval(function(){window .location.reload();}, 60000);

</script>
@endpush