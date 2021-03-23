@extends('layouts.main')

@section('pageTitle', 'Order food now')

@push('headScripts')
<link href="{{asset('css/detail-page.css')}}" rel="stylesheet">
<style>
.back-menu{
	width:100%;
	background-color:gray;
	color:white;
	justify-content: center;
	align-items: center; 
	height:40px;
	display:none;
	
}
@media (max-width: 991px){
	.back-menu{
		display:flex;
	}
}
  .loading {
    border: 16px solid #f3f3f3;
    border-radius: 50%;
    border-top: 16px solid #3498db;
    width: 120px;
    height: 120px;
    -webkit-animation: spin 2s linear infinite; /* Safari */
    animation: spin 2s linear infinite;
  }
  
  /* Safari */
  @-webkit-keyframes spin {
    0% { -webkit-transform: rotate(0deg); }
    100% { -webkit-transform: rotate(360deg); }
  }
  
  @keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
  }

  /* star rating */

  * { box-sizing: border-box; }

.container-stars {
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  justify-content: center;
  padding: 0 20px;
}

.rating-stars {
  display: flex;
  width: 100%;
  justify-content: center;
  overflow: hidden;
  flex-direction: row-reverse;
  height: 150px;
  position: relative;
}

.rating-0 {
  filter: grayscale(100%);
}

.rating-stars > input {
  display: none;
}

.rating-stars > label {
  cursor: pointer;
  width: 40px;
  height: 40px;
  margin-top: auto;
  background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' width='126.729' height='126.73'%3e%3cpath fill='%23e3e3e3' d='M121.215 44.212l-34.899-3.3c-2.2-.2-4.101-1.6-5-3.7l-12.5-30.3c-2-5-9.101-5-11.101 0l-12.4 30.3c-.8 2.1-2.8 3.5-5 3.7l-34.9 3.3c-5.2.5-7.3 7-3.4 10.5l26.3 23.1c1.7 1.5 2.4 3.7 1.9 5.9l-7.9 32.399c-1.2 5.101 4.3 9.3 8.9 6.601l29.1-17.101c1.9-1.1 4.2-1.1 6.1 0l29.101 17.101c4.6 2.699 10.1-1.4 8.899-6.601l-7.8-32.399c-.5-2.2.2-4.4 1.9-5.9l26.3-23.1c3.8-3.5 1.6-10-3.6-10.5z'/%3e%3c/svg%3e");
  background-repeat: no-repeat;
  background-position: center;
  background-size: 76%;
  transition: .3s;
}

.rating-stars > input:checked ~ label,
.rating-stars > input:checked ~ label ~ label {
  background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' width='126.729' height='126.73'%3e%3cpath fill='%23fcd93a' d='M121.215 44.212l-34.899-3.3c-2.2-.2-4.101-1.6-5-3.7l-12.5-30.3c-2-5-9.101-5-11.101 0l-12.4 30.3c-.8 2.1-2.8 3.5-5 3.7l-34.9 3.3c-5.2.5-7.3 7-3.4 10.5l26.3 23.1c1.7 1.5 2.4 3.7 1.9 5.9l-7.9 32.399c-1.2 5.101 4.3 9.3 8.9 6.601l29.1-17.101c1.9-1.1 4.2-1.1 6.1 0l29.101 17.101c4.6 2.699 10.1-1.4 8.899-6.601l-7.8-32.399c-.5-2.2.2-4.4 1.9-5.9l26.3-23.1c3.8-3.5 1.6-10-3.6-10.5z'/%3e%3c/svg%3e");
}


.rating-stars > input:not(:checked) ~ label:hover,
.rating-stars > input:not(:checked) ~ label:hover ~ label {
  background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' width='126.729' height='126.73'%3e%3cpath fill='%23d8b11e' d='M121.215 44.212l-34.899-3.3c-2.2-.2-4.101-1.6-5-3.7l-12.5-30.3c-2-5-9.101-5-11.101 0l-12.4 30.3c-.8 2.1-2.8 3.5-5 3.7l-34.9 3.3c-5.2.5-7.3 7-3.4 10.5l26.3 23.1c1.7 1.5 2.4 3.7 1.9 5.9l-7.9 32.399c-1.2 5.101 4.3 9.3 8.9 6.601l29.1-17.101c1.9-1.1 4.2-1.1 6.1 0l29.101 17.101c4.6 2.699 10.1-1.4 8.899-6.601l-7.8-32.399c-.5-2.2.2-4.4 1.9-5.9l26.3-23.1c3.8-3.5 1.6-10-3.6-10.5z'/%3e%3c/svg%3e");
}

.emoji-wrapper {
  width: 100%;
  text-align: center;
  height: 100px;
  overflow: hidden;
  position: absolute;
  top: 0;
  left: 0;
}

.emoji-wrapper:before,
.emoji-wrapper:after{
  content: "";
  height: 15px;
  width: 100%;
  position: absolute;
  left: 0;
  z-index: 1;
}

.emoji-wrapper:before {
  top: 0;
  background: linear-gradient(to bottom, rgba(255,255,255,1) 0%,rgba(255,255,255,1) 35%,rgba(255,255,255,0) 100%);
}

.emoji-wrapper:after{
  bottom: 0;
  background: linear-gradient(to top, rgba(255,255,255,1) 0%,rgba(255,255,255,1) 35%,rgba(255,255,255,0) 100%);
}

.emoji {
  display: flex;
  flex-direction: column;
  align-items: center;
  transition: .3s;
}

.emoji > svg {
  margin: 15px 0; 
  width: 70px;
  height: 70px;
  flex-shrink: 0;
}

#rating-1:checked ~ .emoji-wrapper > .emoji { transform: translateY(-100px); }
#rating-2:checked ~ .emoji-wrapper > .emoji { transform: translateY(-200px); }
#rating-3:checked ~ .emoji-wrapper > .emoji { transform: translateY(-300px); }
#rating-4:checked ~ .emoji-wrapper > .emoji { transform: translateY(-400px); }
#rating-5:checked ~ .emoji-wrapper > .emoji { transform: translateY(-500px); }

.feedback {
  max-width: 360px;
  background-color: #fff;
  width: 100%;
  padding: 30px;
  border-radius: 8px;
  display: flex;
  flex-direction: column;
  flex-wrap: wrap;
  align-items: center;
  box-shadow: 0 4px 30px rgba(0,0,0,.05);
}

/* 25 subat */
.wrapper.opacity-mask{
	background: -webkit-linear-gradient(top, transparent 5%, rgb(0 0 0 / 90%) 100%)!important;
    background: linear-gradient(to bottom, transparent 5%, rgb(0 0 0 / 90%) 100%)!important;
	top: auto;
	bottom: 0px;
	/*height: 150px;*/
}
@media (max-width:576px){
	.hero_in.detail_page .wrapper .main_info{
		padding-bottom: 15px;
	}
	.hero_in.detail_page {
		height: 200px;
	}
}
</style>

@endpush

@section('content')
<main>

  <div class="hero_in detail_page background-image" data-background="url({{$restaurant->cover[0]}}">
      <div class="wrapper opacity-mask" data-opacity-mask="rgba(0, 0, 0, 0.5)">
          <div class="container">
              <div class="main_info">
                  <div class="row">
                      <div class="col-xl-4 col-lg-5 col-md-6">
                          <div class="head">
                          <a href="#section-reviews">Click to see ratings<br><div class="score"><span>Rating<em>{{$restaurant->rating_count}} Reviews</em></span><strong>{{$restaurant->rating}}</strong></div></a>
                          </div>
                        <h1>{{$restaurant->name}}</h1>
                        {{$restaurant->address_line_1}}, {{$restaurant->address_line_2}}, {{$restaurant->address_postcode}}
                      </div>
                      
                  </div>
              </div>
          </div>
      </div>
  </div>

  <nav class="secondary_nav sticky_horizontal">
    <div class="container">
	<div class="row">
	<div class="col-1 text-right" style="margin:unset; padding:unset;">
	<button class="btn_1 small" id="slideBack" style="width:30px; height:45px;"><</button>
	</div>
	<div class="col-10" style="margin:unset; padding:unset;">
        <ul style="overflow:hidden; overflow-x:auto; white-space:nowrap;" id="secondary_nav">
            @foreach($restaurant->menu as $menu)
              <li style="display:inline-block; font-size:16px;" class="mt-2 mb-2"><b><a class="list-group-item list-group-item-action" href="#{{str_replace(" ","_",$menu->name)}}">{{$menu->name}}</a></b></li>
            @endforeach
            <li style="display:inline-block; font-size:16px;" class="mt-2 mb-2"><b><a class="list-group-item list-group-item-action" href="#section-reviews">Reviews</a></b></li>
        </ul>
	</div>
	<div class="col-1" style="margin:unset; padding:unset;">
	<button class="btn_1 small" id="slide" style=" width:30px; height:45px;">></button>
	</div>
	</div>
    </div>
    <span></span>
  </nav>

  <div class="bg_gray">
    <div class="container margin_detail">
      <div class="row">
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
        <div class="row">
            <div class="col-lg-8 list_menu">
              @foreach($restaurant->menu as $menu)
                <section id="{{str_replace(" ","_",$menu->name)}}">
                  <h4>{{$menu->name}}</h4>
                  <p>{{$menu->description}}</p>
                  <div class="table_wrapper">
                    <table class="table cart-list menu-gallery">
                        <thead>
                            <tr>
                                <th>
                                    Item
                                </th>
                                <th class="text-center">
                                    Price From
                                </th>
                                <th class="text-center">
                                    Order Now
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                          @foreach($menu->items as $item)
                          <tr>
                              <td class="d-md-flex align-items-center">
                                  <div class="flex-md-column">
                                      <h4 class="item-name">{{$item->name}}</h4>
									  
                                      <p>{{$item->available ? $item->description : 'This product is not available at the moment.'}}</p>
                                  </div>
                              </td>
                              <td class="text-center">
                                  <strong>&pound;@money_format($item->base_price)</strong>
                              </td>
                              <td class="text-center">
								@if($item->available)
                                <a href="#" role="button" onclick="showModal({{$item->id}})" data-toggle="modal" data-target="#order-modal" style="font-size:24px;"><i class="icon_plus_alt2"></i></a>
								@else
								<i class="fa fa-times" style="color:red;"></i>
								@endif
                              </td>
                          </tr>
                          @endforeach
                        </tbody>
                    </table>
                  </div>
                </section>
              @endforeach
            </div>

            <div class="col-lg-4" id="sidebar_fixed">
              <div class="box_order mobile_fixed">
                  <div class="head">
                      <h3>Order Summary</h3>
                      <a href="#0" class="close_panel_mobile"><i class="icon_close"></i></a>
                  </div>
                  <!-- /head -->
                  <div class="main" id="basket">
                      @include('restaurants.basket', ['basket' => $basket])
                  </div>
              </div>
              <!-- /box_order -->
              <div class="btn_reserve_fixed"><a href="#0" class="btn_1 gradient full-width" id="view_basket">View Basket({{count($basket->contents)}})</a></div>
          </div>


        </div>
    </div>
  </div>

  <div class="container margin_30_20">
	        <div class="row">
	            <div class="col-lg-8 list_menu">
	                <section id="section-reviews">
	                    <h4>Reviews</h4>
	                    <div class="row add_bottom_30 d-flex align-items-center reviews">
	                        <div class="col-md-3">
	                            <div id="review_summary">
	                                <strong>{{$restaurant->rating}}</strong>
	                                
	                                <small>Based on {{$restaurant->rating_count}} reviews</small>
	                            </div>
	                        </div>
	                    </div>
	                    <!-- /row -->
	                    <div id="reviews">
                          @foreach($restaurant->ratings as $rating)
	                        <div class="review_card">
	                            <div class="row">
	                                <div class="col-md-2 user_info">
	                                    <figure><img src="{{asset('img/avatar.jpg')}}" alt=""></figure>
	                                    <h5>{{$rating->user->name}}</h5>
	                                </div>
	                                <div class="col-md-10 review_content">
	                                    <div class="clearfix add_bottom_15">
	                                        <span class="rating">{{$rating->rating}}<small>/5</small> <strong>Rating</strong></span>
	                                        <em>{{$rating->created_at}}</em>
	                                    </div>
	                                    <h4>"{{$rating->title}}"</h4>
	                                    <p>{{$rating->content}}</p>
	                                    <ul>
	                                        
	                                    </ul>
	                                </div>
	                            </div>
	                            <!-- /row -->
                          </div>
                          @endforeach
	                        <!-- /review_card -->
	                    </div>
	                    <!-- /reviews -->
	                    <div class="text-right"><a href="#" role="button" data-toggle="modal" data-target="#review-modal" class="btn_1 gradient">Leave a Review</a></div>
	                </section>
	                <!-- /section -->
	            </div>
	        </div>
	    </div>

</main>


@endsection

@section('postcontent')
<div class="modal fade" id="order-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"> </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="review-modal" tabindex="-1" aria-labelledby="exampleModalLabel2" aria-hidden="true" style="z-index:99999">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel2">Write a review for "{{$restaurant->name}}"</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{route('restaurants.submit-review',$restaurant->slug)}}" method="POST">
        @include('restaurants.rating')
        {{csrf_field()}}
        <div class="form-group">
          <label>Title of your review</label>
          <input class="form-control" type="text" name="title" placeholder="If you could say it in one sentence, what would you say?">
        </div>
        <div class="form-group">
          <label>Your review</label>
          <textarea class="form-control" rows="3" name="content" placeholder="Write your review to help others learn about this online business"></textarea>
        </div>
        <input type="submit" class="btn_1" value="Submit Review" style="float:right;">
        </form>
      </div>
    </div>
  </div>
</div>

@endsection

@push('footerScripts')
<script src="{{asset('js/sticky_sidebar.min.js')}}"></script>
<script src="{{asset('js/sticky-kit.min.js')}}"></script>
<script src="{{asset('js/specific_detail.js')}}"></script>

<script>

  $(document).ready(function() {
    var width=$(window).width();
    var words=10;
    $('.item-name').each(function() {
      var text=$(this).text();
      var split=text.split(" ");
      var count=0;
      if(width>300 && width<=450)
      words=2;
      if(width>450 && width<=600)
      words=3;
      if(width>600 && width<=750)
      words=4;

      text="";
      split.forEach(element => {
        if(count != words){
          text+=element + " ";
          count++;
        }else{
          text+="<br>" + element + " ";
          count=0;
        }
      });
      $(this).html(text);
    });
   });

  var fulfilment_method="{{$basket->fulfilment_method}}";
  var basketItems= {!!json_encode($basket->contents)!!};
  var basketItem;
  var copydata;
  function showModal(id,edit=false){
    $("#order-modal").css("z-index","9999999");
    var modal=$("#order-modal");
    $.ajax('{{url('/ajax/menu/')}}/'+id, 
{
    dataType: 'json', // type of response data
    timeout: 10000,     // timeout milliseconds

    beforeSend: function() {
      modal.find('.modal-title').text("Loading");
      modal.find('.modal-body').html("<div class='loading'></div>");
      modal.find('.modal-body').css('margin','0 auto');
    },

    success: function (data,status,xhr) {   // success callback function
      var control=0;
      jQuery(basketItems).each(function (index){
        if(index==(edit-1)){
          basketItem=basketItems[index];
          control=1;
            return false; // This will stop the execution of jQuery each loop.
        }
        });

        if(control==0 && !edit)
        basketItem={
          "id" : data.id,
          "name" : data.name,
          "description" : data.description,
          "flags" : data.flags,
          "sizes" : "",
          "options" : data.options,
          "restaurant_id" : data.restaurant_id,
          "menu_group_id" : data.menu_group_id,
          "base_price" : data.base_price,
          "total_price" : data.base_price

        };
        
        if(data.options.length==0 && data.sizes.length<2){
          basketItems.push(basketItem);
          $.ajax('{{url('/ajax/updateBasket/')}}', 
      {
        dataType: 'html', // type of response data
        timeout: 10000,     // timeout milliseconds
        type:'POST',
        data: {
          basket_id : '{{$basket->id}}',
          customerOrderItems : basketItems,
          fulfilment_method : fulfilment_method,
          _token : '{{csrf_token()}}'
          
        },
        beforeSend: function() {
          modal.find('.modal-title').text("Adding to Basket");
          modal.find('.modal-body').html("<div class='loading'></div>");
          modal.find('.modal-body').css('margin','0 auto');
        } ,
        success: function (data,status,xhr) {
          $("#basket").html(data);
          $("#view_basket").text("View Basket("+basketItems.length+")");
          setTimeout(function(){modal.modal('hide')}, 100);
        },
        error: function (jqXhr, textStatus, errorMessage) { // error callback 
          }
      });
        return false;
        }
        copydata=data;
        modal.find('.modal-title').text(data.name);
        modal.find('.modal-body').css('margin','0');
        var showhtml="";
        if(data.sizes.length>1 && !edit){
          showhtml+="<div id='page-size'><h6>Select Size</h6>";
            data.sizes.forEach(function(size) {
              showhtml+="<div class='row mt-1'>";
              showhtml+="<div class='col-9'><input onclick='selectSize(" + size.id +")' type='radio' name='item-" + size.menu_item_id + "' id='size-"+ size.id +"'> <label for='size-" + size.id + "'>" + size.label + "</label></div>" + (size.additional_charge>0 ? "<div class='col-3'> &pound;"+ (data.base_price+size.additional_charge).toFixed(2) + "</div>" : "<div class='col-3'> &pound;"+ data.base_price.toFixed(2) + "</div>");
              showhtml+="</div>"
            });
          showhtml+="</div>";
        }else if(data.sizes.length==1){
          basketItem.sizes=data.sizes[0].label;
        }


        var page=0;
        data.options.forEach(function(option){
          if(option.name=="Choose your salad" || option.name.includes("choose your salad")){
            var optionid=Math.floor(Math.random() * 999999);
            showhtml+="<div id='page-" +page+ "' style='display:none;'>";
              showhtml+="<h6>"+option.name+"</h6>";
              var count=0;
              option.option_values.forEach(function(value){
                var checked=0;
                basketItem.options[page].option_values.forEach(function (itemoption){
                if(itemoption.id==value.id && itemoption.selected=="true"){
                  checked=1;
                }
                
            });
            showhtml+="<div class='row "+ ((count>1 && count<7) ? 'withsalad-'+optionid : ( count>6 ? 'nosalad-'+optionid : '')) +"' style='display:"+(count<2 ? 'block' : 'none')+"'>";
            showhtml+="<div class='col-10' " + (count<2 ? "onclick='saladOptions("+count+","+optionid+")'" : "") +"><input class='"+ ((count>1 && count<7) ? 'withsaladcheck-'+optionid : ( count>6 ? 'nosaladcheck-'+optionid : ''))+"' type='" + (count<2 ? 'radio' : 'checkbox') + "' name='" + option.name + "' id='value-" + value.id + "' "+ (checked ? "checked" : "") +"> <label for='value-" + value.id + "'>" + value.name + "</label> </div>" + (value.additional_charge>0 ? "<div class='col-2'>&pound;"+ value.additional_charge.toFixed(2) +"</div>" : "");
            showhtml+="</div>";
            count++;
          });

          }else{
          var inputType;
          if(option.allow_multiple==false)
          inputType="radio";
          else
          inputType="checkbox";

          showhtml+="<div id='page-" +page+ "' style='display:none;'>";

          showhtml+="<h6>"+option.name+"</h6>";
		  if(option.description!=null)showhtml+="<small>"+option.description+"</small>";
          option.option_values.forEach(function(value){
            var checked=0;
            basketItem.options[page].option_values.forEach(function (itemoption){
              if(itemoption.id==value.id && itemoption.selected=="true"){
                checked=1;
                
              } 
            });
            showhtml+="<div class='row'>";
            showhtml+="<div class='col-10'><label for='value-" + value.id + "' > <input type='" + inputType + "' name='" + option.name + "' id='value-" + value.id + "' "+ (checked ? "checked" : "") +"> " + value.name + "</label> </div>" + (value.additional_charge>0 ? "<div class='col-2' id='addid-"+value.id+"'>&pound;"+ value.additional_charge.toFixed(2) +"</div>" : "");
            showhtml+="</div>";
			if(value.description!=null)
			showhtml+="<div class='row' style='margin-top:-5px;'><div class='col-12'><small>"+value.description+"</small></div></div>";
          });
          }

          showhtml+="<br><a href='javascript:void(0)' onclick='previousPage("+ page +")' style='float:left;'>Back</a>";
          showhtml+="<a href='javascript:void(0)' onclick='nextPage("+ page +","+ data.options.length + "," + edit +")' style='float:right;'>Next</a> ";
          showhtml+="</div>";
          page++;

        });
         modal.find('.modal-body').html(showhtml);
        
         if(!$("#page-size").length)
          $("#page-0").show();

    },
    error: function (jqXhr, textStatus, errorMessage) { // error callback 
        
    }
});

  }

  function saladOptions(count,id){
    if(count==0){
      $('.withsalad-'+id).each(function(){
        $(this).css('display','block');
      });
      $('.nosalad-'+id).each(function(){
        $(this).css('display','none');
      });
      $('.nosaladcheck-'+id).each(function(){
        $(this).prop("checked",false);
      });
    }else{
      $('.withsalad-'+id).each(function(){
        $(this).css('display','none');
      });
      $('.nosalad-'+id).each(function(){
        $(this).css('display','block');
      });
      $('.withsaladcheck-'+id).each(function(){
        $(this).prop("checked",false);
      });
    }
  }

  function selectSize(id){
    var count=0
    copydata.sizes.forEach(function(size){
      if(size.id==id) {
        basketItem.sizes=size.label;
        basketItem.total_price+=size.additional_charge;
        $('#order-modal').find('.modal-title').text(copydata.name + " / " + size.label);

        if(copydata.name.includes('Pizza')){
          
          copydata.options.forEach(function(option){
            option.option_values.forEach(function(value){
              if(value.additional_charge!=0){
                if(count!=0){
                  if(value.name=="Cheese stuffed crust"){
                    value.additional_charge = count==1 ? 2.30 : 2.80;
                  }else{
                    value.additional_charge = count==1 ? 1.20 : 1.70;
                  }
                }
                $('#order-modal').find('#addid-'+value.id).html("&pound;" + value.additional_charge.toFixed(2));
              }
            });
          
          });
        }


        }
        count++;
    });
    $("#page-size").hide();
    if($("#page-0").length)
    $("#page-0").show();
    else{
		var modal=$("#order-modal");
		basketItems.push(basketItem);
          $.ajax('{{url('/ajax/updateBasket/')}}', 
      {
        dataType: 'html', // type of response data
        timeout: 10000,     // timeout milliseconds
        type:'POST',
        data: {
          basket_id : '{{$basket->id}}',
          customerOrderItems : basketItems,
          fulfilment_method : fulfilment_method,
          _token : '{{csrf_token()}}'
          
        },
        beforeSend: function() {
          modal.find('.modal-title').text("Adding to Basket");
          modal.find('.modal-body').html("<div class='loading'></div>");
          modal.find('.modal-body').css('margin','0 auto');
        } ,
        success: function (data,status,xhr) {
          $("#basket").html(data);
          $("#view_basket").text("View Basket("+basketItems.length+")");
          setTimeout(function(){modal.modal('hide')}, 100);
        },
        error: function (jqXhr, textStatus, errorMessage) { // error callback 
          }
      });
   
	}
  }

  function nextPage(current,all,edit){
    if(copydata.options[current].allow_multiple==false){
      var control=0;
      for(var i=0; i<copydata.options[current].option_values.length; i++){
        
        var values=document.getElementsByName(copydata.options[current].name);
        if(basketItem.options[current].option_values[i].selected=="true"){
          basketItem.options[current].option_values[i].selected="false";
          basketItem.total_price-=copydata.options[current].option_values[i].additional_charge;
        }
        if(values[i].checked){
          basketItem.options[current].option_values[i].selected="true";
          basketItem.total_price+=copydata.options[current].option_values[i].additional_charge;
          control=1;
        }
      }
      if(control==0 && copydata.options[current].required==true){
        alert("Please Select At Least One Option");
        return;
      }
    }else{
      var count=0;
      for(var i=0; i<copydata.options[current].option_values.length; i++){
        var values=document.getElementsByName(copydata.options[current].name);
        if(values[i].checked){
          count++
        }
      }
      if(copydata.options[current].multiple_limit!=null && count>copydata.options[current].multiple_limit){
        alert("You Can Select Only " + copydata.options[current].multiple_limit + " Options" );
        return;
      }
      if(count==0 && copydata.options[current].required==true){
        alert("Please Select At Least One Option");
        return;
      }
      for(var i=0; i<copydata.options[current].option_values.length; i++){
        var values=document.getElementsByName(copydata.options[current].name);
        if(basketItem.options[current].option_values[i].selected=="true"){
          basketItem.options[current].option_values[i].selected="false";
          basketItem.total_price-=copydata.options[current].option_values[i].additional_charge;
        }
        if(values[i].checked){
          basketItem.options[current].option_values[i].selected="true";
          basketItem.total_price+=copydata.options[current].option_values[i].additional_charge;
        }
      }
    }

    if(current!=all-1){
      $("#page-"+ current).hide();
      $("#page-"+ (current+1)).show();
    }else{
      $("page-" + current).hide();
      if(!edit) basketItems.push(basketItem);
      else {
        basketItems[edit-1]=basketItem
      }
      $("#view_basket").text("View Basket("+basketItems.length+")");
      var modal=$("#order-modal");
      $.ajax('{{url('/ajax/updateBasket/')}}', 
      {
        dataType: 'html', // type of response data
        timeout: 10000,     // timeout milliseconds
        type:'POST',
        data: {
          basket_id : '{{$basket->id}}',
          customerOrderItems : basketItems,
          fulfilment_method : fulfilment_method,
          _token : '{{csrf_token()}}'
          
        },
        beforeSend: function() {
          modal.find('.modal-title').text("Adding to Basket");
          modal.find('.modal-body').html("<div class='loading'></div>");
          modal.find('.modal-body').css('margin','0 auto');
        } ,
        success: function (data,status,xhr) {
          $("#basket").html(data);
          setTimeout(function(){modal.modal('hide')}, 100);
        },
        error: function (jqXhr, textStatus, errorMessage) { // error callback 
          }
      });
    }
  }

  function previousPage(current){
    if(current==0){
      /*
      if($("#page-size").length){
        $("#page-"+current).hide();
        $("#page-size").show();
      }else $("#order-modal").modal('hide');
      */
      $("#order-modal").modal('hide');
    }else{
      $("#page-"+current).hide();
      $("#page-"+ (current-1)).show();
    }
  }

  function updateFulfilmentMethod(to){
    fulfilment_method=to;
    $("#order-modal").css("z-index","9999999");
    var modal=$("#order-modal");
    $.ajax('{{url('/ajax/updateBasket/')}}', 
      {
        dataType: 'html', // type of response data
        timeout: 10000,     // timeout milliseconds
        type:'POST',
        data: {
          basket_id : '{{$basket->id}}',
          customerOrderItems : basketItems,
          fulfilment_method : fulfilment_method,
          _token : '{{csrf_token()}}'
          
        },
        beforeSend: function() {
          modal.modal('show');
          modal.find('.modal-title').text("Updating");
          modal.find('.modal-body').html("<div class='loading'></div>");
          modal.find('.modal-body').css('margin','0 auto');
        } ,
        success: function (data,status,xhr) {
          $("#basket").html(data);
          $("#view_basket").text("View Basket("+basketItems.length+")");
          setTimeout(function(){modal.modal('hide')}, 100);
        },
        error: function (jqXhr, textStatus, errorMessage) { // error callback 
          }
  
      });

      

  }

  function removeItem(id){
	var result = confirm("Do you want to remove this from your basket?");
	if(result){
    var modal=$("#order-modal");
    $("#order-modal").css("z-index","9999999");
    jQuery(basketItems).each(function (index){
        if(basketItems[index].id == id){
          basketItems.splice(index,1); 
          
            return false; // This will stop the execution of jQuery each loop.
        }
    });

    $.ajax('{{url('/ajax/updateBasket/')}}', 
      {
        dataType: 'html', // type of response data
        timeout: 10000,     // timeout milliseconds
        type:'POST',
        data: {
          basket_id : '{{$basket->id}}',
          customerOrderItems : basketItems!=null ? basketItems : '[]',
          fulfilment_method : fulfilment_method,
          _token : '{{csrf_token()}}'
          
        },
        beforeSend: function() {
          modal.modal('show');
          modal.find('.modal-title').text("Removing From Basket");
          modal.find('.modal-body').html("<div class='loading'></div>");
          modal.find('.modal-body').css('margin','0 auto');
        } ,
        success: function (data,status,xhr) {
          $("#basket").html(data);
          $("#view_basket").text("View Basket("+basketItems.length+")");
          setTimeout(function(){modal.modal('hide')}, 100);
        },
        error: function (jqXhr, textStatus, errorMessage) { // error callback 
          }
      });
    }  
  }
  
  var button = document.getElementById('slide');
button.onclick = function () {
    var container = document.getElementById('secondary_nav');
    sideScroll(container,'right',25,300,10);
};

var back = document.getElementById('slideBack');
back.onclick = function () {
    var container = document.getElementById('secondary_nav');
    sideScroll(container,'left',25,300,10);
};
  
  function sideScroll(element,direction,speed,distance,step){
    scrollAmount = 0;
    var slideTimer = setInterval(function(){
        if(direction == 'left'){
            element.scrollLeft -= step;
        } else {
            element.scrollLeft += step;
        }
        scrollAmount += step;
        if(scrollAmount >= distance){
            window.clearInterval(slideTimer);
        }
    }, speed);
}

</script>

@endpush