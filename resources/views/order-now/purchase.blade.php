@extends('layouts.main')

@section('pageTitle', 'Pay and complete your order')

@push('headScripts')
  <link href="{{asset('css/order-sign_up.css')}}" rel="stylesheet">
  <link href="{{asset('css/detail-page.css')}}" rel="stylesheet">
  <script src="https://js.stripe.com/v3/"></script>
  <style>
  .my-number-input {
    margin-top:20px;
    padding-left:10px;
    margin:10px;
    border-radius:20px;
    background-color:white;
  }
  .my-ce-input {
    border-radius:20px;
    background-color:white;
    margin:10px;


  }
  </style>
@endpush



@section('content')

<main class="bg_gray pt-5">
<form enctype="multipart/form-data" class="payment" method="post" action="{{ route('restaurants.pay') }}"> 
    {{csrf_field()}}
  <div class="container margin_60_20">
    <div class="row justify-content-center">
      <div class="col-12">
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
      </div>
    </div>
      <div class="row justify-content-center">
          <div class="col-xl-6 col-lg-8">
            <div class="box_order_form">
              <div class="head">
                <div class="title">
                    <h3>Personal Details</h3>
                </div>
              </div>
              <!-- /head -->
              <div class="main">
                <div class="form-group">
                    <label>First Name</label>
                    <input class="form-control" name="first_name" value="{{ $user->first_name }}" placeholder="First Name">
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Last Name</label>
                            <input class="form-control" name="last_name" value="{{ $user->last_name }}" placeholder="Last Name">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Contact Number</label>
                            <input class="form-control" name="telephone" value="{{ $user->contact_number }}" placeholder="Phone">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                  <label>Desired time for {{ $basket->fulfilment_method }}</label>
                  <select class="form-control" name="desired_time" id="desired_time">
                    <option value="{{ time() }}">ASAP</option>
                    @foreach ($times as $human => $timestamp)
                      <option value="{{ $timestamp }}">{{ $human }}</option>
                    @endforeach
                  </select>
                </div>

                <div class="form-group">
                  <label>Leave a note for the restaurant</label>
                  <textarea name="notes" class="form-control"  placeholder="e.g. the doorbell doesn't work.do not include details about any allergies here."></textarea>
                </div>

                @if ($basket->fulfilment_method == 'delivery')
			            @if(count($user->addresses))
				            <div class="form-group">
				              <label for="addressid">Select Address</label>
				              <select name="addressid" class="form-control" id="addressid" onchange="selectAddress()">
			                	@foreach($user->addresses as $address)
					                <option value="{{$address->id}}">{{$address->name}}</option>
				                @endforeach
				              </select>
				            </div>
				
				            @foreach($user->addresses as $address)
					            <div class="address" style="width:300px; border:dotted 1px; padding:20px; margin-top:5px; display:{{$loop->first ? 'block':'none'}};" id={{$address->id}}>
						            <b>Address Line 1:</b><br>
						            {{$address->address_line1}}<br>
						            <b>Address Line 2:</b><br>
						            {{$address->address_line2}}<br>
						            <b>City:</b><br>
						            {{$address->city}}<br>
						            <b>Postcode:</b><br>
						            {{$address->postcode}}<br>
					            </div>
				
				            @endforeach
                  @else
                  You don't have any saved addresses. You can save addresses <a href="{{route('user.savedAddresses')}}" target="_blank">here</a>.
                    <div class="form-group">
                        <label>Full Address</label>
                        <input class="form-control" name="delivery[address_line_1]" value="{{old('delivery.address_line_1')}}" placeholder="Address Line 1">
                        <input class="form-control" name="delivery[address_line_2]" value="{{old('delivery.address_line_2')}}" placeholder="Address Line 2">
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>City</label>
                                <input class="form-control" name="delivery[city]" value="{{old('delivery.city')}}" placeholder="City">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Postal Code</label>
                                <input class="form-control" name="delivery[postcode]" value="{{old('delivery.postcode')}}" placeholder="PO21">
                            </div>
                        </div>
                    </div>
                  @endif
                @endif
              </div>
            </div>
        <!-- /box_order_form -->
            <div class="box_order_form">
              <div class="head">
                <div class="title">
                    <h3>Payment Method</h3>
                </div>
              </div>
            <!-- /head -->
              <div class="main">
                <div class="payment_select">
                  <label class="container_radio">Credit Card
                    <input type="radio" value="card" name="paymentMethod" id="pay_card" onclick="showBilling()" checked>
                    <span class="checkmark"></span>
                  </label>
                  <i class="icon_creditcard"></i>
                </div>
              
                <div class="payment_select">
                  <label class="container_radio">Pay with Cash
                    <input type="radio" name="paymentMethod" id="pay_cash" value="cash" onclick="$('#payment_credit').hide()">
                    <span class="checkmark"></span>
                  </label>
                  <i class="icon_wallet"></i>
                </div>

                
                <div id="payment_credit">
                  <div id="card-errors" role="alert"></div>

                    <div style="background-color:#eceef2">
                      <div class="row">
                        <div class="col-12">
                          <div class="form-group">
                          <div id="card-number" class="my-number-input"></div>
                          </div>
                        </div>
                      </div>
                      
                      <div class="row">
                        <div class="col-6">
                        <div class="form-group">
                          <div id="card-expiry" class="my-ce-input"></div>
                          </div>
                        </div>
                        <div class="col-6">
                        <div class="form-group">
                          <div id="card-cvc" class="my-ce-input"><i class='fas fa-lock'></i></div>
                        </div>
                        </div>
                      </div>
                    
                  <div class="form-group">
                    <label for="remember_card">
                    <input type="checkbox" name="remember_card" id="remember_card"> &nbsp;Save this card to checkout faster next time</label>
                  </div>
                </div>

                  <h4>Billing address</h4>
                  @if(count($user->addresses))
                    <div class="form-group">
                      <label for="addressid">Select Address</label>
                      <select name="billingaddressid" class="form-control" id="billingaddressid" onchange="selectBillingAddress()">
                        @foreach($user->addresses as $address)
                          <option value="{{$address->id}}">{{$address->name}}</option>
                        @endforeach
                      </select>
                    </div>
            
                    @foreach($user->addresses as $address)
                      <div class="billingaddress" style="width:300px; border:dotted 1px; padding:20px; margin-top:5px; display:{{$loop->first ? 'block':'none'}};" id="billing{{$address->id}}">
                        <b>Address Line 1:</b><br>
                        {{$address->address_line1}}<br>
                        <b>Address Line 2:</b><br>
                        {{$address->address_line2}}<br>
                        <b>City:</b><br>
                        {{$address->city}}<br>
                        <b>Postcode:</b><br>
                        {{$address->postcode}}<br>
                      </div>
                    @endforeach
                  @else

                  <div class="form-group">
                    <label>Full Address</label>
                    <input class="form-control" name="card_address_line_1" value="{{old('card_address_line_1')}}" placeholder="Address Line 1">
                    <input class="form-control" name="card_address_line_2" value="{{old('card_address_line_2')}}" placeholder="Address Line 2">
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>City</label>
                        <input class="form-control" name="card_address_city" value="{{old('card_address_city')}}" placeholder="City">
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label>Postal Code</label>
                        <input class="form-control" name="card_address_postcode" value="{{old('card_address_postcode')}}" placeholder="PO21">
                      </div>
                    </div>
                  </div>
                  @endif
      

                </div>

              </div>
            </div>
        <!-- /box_order_form -->
          </div>
          <!-- /col -->
          <div class="col-xl-4 col-lg-4" id="sidebar_fixed">
              <div class="box_order">
                  <div class="head">
                      <h3>Order Summary</h3>
                  </div>
                  <!-- /head -->
                  <div class="main">
                    <ul class="clearfix">

                      @foreach ($basket->contents as $orderItem)
                      <li>
                        <b>{{$orderItem['name']}}</b>
                        {{$orderItem['sizes']}}
                        <span>&pound; @money_format($orderItem['total_price'])</span><br>
                        <small>{{$orderItem['description']}}</small>
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
                                    <i class="fas fa-check"></i>
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
                      </li>
                      @endforeach
                      
                      <li>
                      @if ($basket->fulfilment_method == "collection")
                        <b><i>Collection</i></b>
                        <span><b><i>Free</i></b></span>
                      @else
                        @if($basket->restaurant->delivery_fee > 0)
                          <i class="fas fa-plus"></i><b><i>Delivery</i></b>
                          <span><i>&pound; @money_format($basket->restaurant->delivery_fee) </i></span>
                        @else
                         <b><i>Delivery</i></b>
                          <span><b><i>Free</i></b></span>
                        @endif
                      @endif
                      </li>

                      @if($basket->restaurant->service_charge > 0)
                      <li>
                            <i class="fas fa-plus"></i>
                            <b><i>Service charge</i></b>
                          <span><i>&pound;@money_format($basket->restaurant->service_charge)</i></span>
                      </li>
                      @endif
					  @php
						$discounted=$basket->discounted_total;
						$discounted_total=$discounted['discounted_total'];
						$discount=$discounted['discount'];
						
					  @endphp
                      <li class="total">Total<span>&pound; @money_format($basket->total)</span></li>
					  <li class="total">%25 Discount<span>&pound; @money_format($discount)</span></li>
					  <li class="total">New Total<span>&pound; @money_format($discounted_total)</span></li>
                    </ul>
                    
                    <input type="hidden" name="basket_id" value="{{ $basket->id }}">
                    <input type="hidden" name="basket_hash" value="{{ $basket->hash }}">
                    <button type="submit" class="btn_1 gradient full-width mb_5">Order Now</button>
                  
                  </div>
              </div>
              <!-- /box_booking -->
          </div>

      </div>
      <!-- /row -->
  </div>
  <!-- /container -->
</form>
</main>

@endsection

@push('footerScripts')

  <script src="{{asset('js/sticky_sidebar.min.js')}}"></script>
  <script>
    $('#sidebar_fixed').theiaStickySidebar({
		  minWidth: 991,
		  updateSidebarHeight: false,
		  additionalMarginTop: 80
		});
  </script>

  <script>
	
	function selectAddress(){
	var addressid=document.getElementById('addressid');
	var id=addressid.value;
	
	$('.address').hide();
	var displaydiv=document.getElementById(id);
	displaydiv.style.display="block";
	}
	
	function selectBillingAddress(){
	var addressid=document.getElementById('billingaddressid');
	var id=addressid.value;
	
	$('.billingaddress').hide();
	var displaydiv=document.getElementById("billing"+id);
	displaydiv.style.display="block";
	}
	
	function showBilling(){
    $('#payment_credit').show();
		var id=document.getElementById('billingaddressid').value;
		var showid="#billing"+id;
		$(showid).show();
	}
  
  
    var paymentForm = document.querySelector('.content form');

    function toggleCardInputs() {
        var paymentType = document.querySelector('[name="paymentMethod"]:checked').value;
        paymentForm.classList.remove('payment--cash','payment--card');
        paymentForm.classList.add('payment--'+paymentType);
    };

    document.querySelectorAll('[name="paymentMethod"]').forEach(function(el){
        el.addEventListener('change', toggleCardInputs)
    });
    toggleCardInputs();
    var style={
        base: {
          iconColor: '#404040',
          color: '#404040',
          fontWeight: 'bolder',
          fontFamily: 'Quicksand, sans-serif',
          fontSize: '18px', 
          fontSmoothing: 'antialiased',
          textAlign:'center',
          lineHeight:'40px',
          ':-webkit-autofill': {
            color: '#fce883',
          },
          '::placeholder': {
            color: '#404040',
          },
        },
        invalid: { 
          iconColor: '#BB332F',
          color: '#BB332F',
        },
        complete: {
          iconColor: '#65AD55',
          color: '#65AD55',
        },
      };
    var stripe = Stripe( '{{ Config::get('services.stripe.public') }}' );
    var elements = stripe.elements();
    var cardNumberElement = elements.create('cardNumber',{
      showIcon: true,
      style: style ,
      placeholder:'Card Number',
    });
    cardNumberElement.mount('#card-number');
    var cardExpiryElement = elements.create('cardExpiry',{
      style: style,
    });
    cardExpiryElement.mount('#card-expiry');
    var cardCvcElement = elements.create('cardCvc',{
      style: style,
    });
    cardCvcElement.mount('#card-cvc');

    var form = document.querySelector('form.payment');
    form.addEventListener('submit', function(event) {
      event.preventDefault();

      var paymentMethod = form.querySelector('[name="paymentMethod"]:checked').value;

      if (paymentMethod === "cash") {
        form.submit();
        return true;
      }

      stripe.createToken(cardNumberElement,cardCvcElement,cardExpiryElement).then(function(result) {
        if (result.error) {
          var errorElement = document.getElementById('card-errors');
          errorElement.textContent = result.error.message;
        } else {
          stripeTokenHandler(result.token);
        }
      });
    });
    function stripeTokenHandler(token) {
      var hiddenInput = document.createElement('input');
      hiddenInput.setAttribute('type', 'hidden');
      hiddenInput.setAttribute('name', 'stripe_token');
      hiddenInput.setAttribute('value', token.id);
      form.appendChild(hiddenInput);
      form.submit();
    }

  </script>
@endpush
