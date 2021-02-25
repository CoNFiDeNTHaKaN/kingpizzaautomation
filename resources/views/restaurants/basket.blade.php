    <ul class="clearfix">
        @if(count($basket->contents)==0)
        <li>You don't have any items in your basket.</li>
        @else
        
        @foreach($basket->contents as $item)
        <li class="text-right">@if(array_key_exists('options',$item))<button data-toggle="modal" data-target="#order-modal" class="btn_1 small" onclick="showModal({{$item['id']}},{{$loop->iteration}})">Edit</button>@endif <button class="btn_1 small" onclick="removeItem({{$item['id']}})">Remove</button></li>
        <li><b>{{$item['name']}}</b>@if($item['sizes']) ({{$item['sizes']}}) @endif<span>&pound;@money_format($item['total_price'])</span><br><small>{{$item['description']}}</small></li>
          <ul>
            @if(array_key_exists('options',$item))
            @foreach ($item['options'] as $option)
              @foreach ($option['option_values'] as $value)
                @if($value['selected']=="true")
                  <li class="ml-2"><i class="fas fa-check" style="color:green"></i> {{$value['name']}}{!!$value['additional_charge']==0 ? '': '(+&pound;'.$value['additional_charge'].')'!!}</li>
                @endif
              @endforeach
            @endforeach
            @endif
          </ul>
        @endforeach
        @endif
    </ul>
    <ul class="clearfix">
        <li>Subtotal<span>&pound;@money_format($basket->item_total)</span></li>
        <li>Delivery fee<span>&pound;{{$basket->fulfilment_method=='collection' ? '0' : $basket->restaurant->delivery_fee}}</span></li>
        @if($basket->restaurant->service_charge > 0)
        <li>
              
              Service charge
            <span><i>&pound;@money_format($basket->restaurant->service_charge)</i></span>
        </li>
        @endif
        @php
		    $discounted=$basket->discounted_total;
		    $discounted_total=$discounted['discounted_total'];
		    $discount=$discounted['discount'];
			
				@endphp
        <li class="total">Total<span>&pound; @money_format($basket->total)</span></li>
        @if(count($basket->contents)>0)
		    <li class="total">%25 Discount<span>&pound; @money_format($discount)</span></li>
        <li class="total">New Total<span>&pound; @money_format($discounted_total)</span></li>
        @endif
    </ul>
    <div class="row opt_order mb-5">
        <div class="col-6">
            <label class="container_radio">Delivery
                <input type="radio" value="option1" name="opt_order" onclick="updateFulfilmentMethod('delivery')" {{$basket->fulfilment_method=='delivery' ? 'checked' : ''}} {{$basket->restaurant->delivery_now ? '' : 'disabled'}}>
                <span class="checkmark"></span>
            </label>
        </div>
        <div class="col-6">
            <label class="container_radio">Take away
                <input type="radio" value="option1" name="opt_order" onclick="updateFulfilmentMethod('collection')" {{$basket->fulfilment_method=='collection' ? 'checked' : ''}} {{$basket->restaurant->order_now ? '' : 'disabled'}}>
                <span class="checkmark"></span>
            </label>
        </div>
    </div>
    <!-- /dropdown -->
    <div class="btn_1_mobile">
	<a href="#0" class="back-menu" onclick="$('.box_order').hide()">Back To Menu</a>
        <a href="{{route('restaurants.checkout')}}" class="btn_1 gradient full-width mb_5">Go To Checkout</a>
        <div class="text-center"><small>No money charged on this steps</small></div>
    </div>
