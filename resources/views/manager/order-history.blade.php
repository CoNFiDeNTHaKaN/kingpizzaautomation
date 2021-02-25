@extends('templates.manager')

@section('pageTitle', 'Live orders')

@push('bodyClasses') orders-history @endpush

@section('content')
  <div id="order-history" class="wrapper">
    <div class="flex flex--v-center flex--spread">
      <h1>Eat Kebab Online history orders</h1>
      <input type="search" placeholder="Search customer name" name="customer_name" v-model="searchText" v-bind:class="[filteredOrders.length == 0 ? 'invalid' : 'valid' ]">
    </div>

    <div v-bind:class="[orderToConfirm ? 'orders-history--order-being-confirmed' : '']">

      <div class="row order-history" v-for="order in filteredOrders" v-bind:class="[ order == orderToConfirm ? 'order-history--being-confirmed' : '']">
        <div class="order-history__details col-12 col-sm-9">
          <div class="order-history__details__headlines flex flex--v-bottom flex--spread">
            <span>@{{ order['user']['name'] }} (#@{{ order['id'] }})</span>
            <span>Placed at @{{ order['created_at'] | date() }}</span>
            <template v-if="order['payment_type'] === 'cash'">
              <template v-if="order['paid']">
                <span>Cash paid <b>&pound;@{{order['basket']['total']}}</b></span>
              </template>
              <template v-else>
                <span>Cash due on @{{ order['fulfilment_method'] }} <b>&pound;@{{order['basket']['total']}}</b></span>
              </template>
            </template>
            <template v-else>
              <template v-if="order['paid']">
                <span>Card payment successful <b>&pound;@{{ order['basket']['total'] }}</b></span>
              </template>
              <template v-else>
                <span>Card payment failed <b>&pound;@{{ order['basket']['total'] }}</b></span>
              </template>
            </template>
          </div>
          <div class="order-history__details__items">
            <b>Order Items</b>
            <div class="restaurant-order__item" v-for="orderItem in order['basket']['contents']">
              <span class="restaurant-order__item__name"><b>@{{orderItem['name']}}</b></span>
              <span class="restaurant-order__item__size">@{{orderItem['sizes']}}</span>
              <span class="restaurant-order__item__price">&pound;@{{ orderItem['total_price'].toFixed(2) }}</span>

              <template v-for="option in orderItem['options']">
                <template v-for="optionValue in option['option_values']">

                  <div class="restaurant-order__item__options" v-if="optionValue['selected'] == true">
                    <i class="fas fa-check t-green"></i>
                    <span class="restaurant-order__item__options__name"><b>@{{optionValue['name']}}</b> | <i>@{{ option['name'] }}</i></span>
                    <template v-if="optionValue['additional_charge'] > 0">
                      <span class="restaurant-order__item__options__price">(&plus;&pound;@{{ optionValue['additional_charge'].toFixed(2) }})</span>
                    </template>
                  </div>

                </template>
              </template>
            </div>
          </div>
        </div>

        <div class="order-history__delivery col-12 col-sm-3">
          <div class="order-history__deliver__time t-right">
            <b>@{{ order['fulfilment_method'].toUpperCase() }}</b>
          </div>
          <div class="order-history__deliver__address">
            <template v-if="order['fulfilment_method'] == 'delivery'">
              <br>
              <p><b>Delivery address</b><br>
              @{{ order['delivery_line1'] }},<br>
              <template v-if="order['delivery_line2']">
                @{{ order['delivery_line2'] }},<br>
              </template>
              @{{ order['delivery_city'] }},<br>
              @{{ order['delivery_postcode'] }}<br>
              </p>
            </template>
          </div>
        </div>
        <div class="order-history__actions col-12 flex flex--v-center flex--spread">

        </div>
      </div>

      <div v-if="filteredOrders.length == 0" >
        <br>
        <br>
        <h2 class="t-center">No orders match that search.</h2>
        <br>
        <br>
        <br>
      </div>

    </div>
  </div>
@endsection

@push('footerScripts')
  <script>
    const manageOrders = new Vue({
      el : '#order-history',
      data : {
        orders : [],
        confirmingOrder: false,
        orderToConfirm : false,
        modalOpen : false,
        searchText : ""
      },
      methods : {
        addLeadingZero : function () {
          var input = event.target;
          input.value = input.value.length < 2 ? "0" + input.value : input.value;
        }
      },
      computed : {
        filteredOrders() {
          return this.orders.filter(order => {
            return order.user.name.toLowerCase().includes(this.searchText.toLowerCase());
          });
        }
      },
      mounted : function () {
        var self = this;
        var getOrders = axios.get("{{ route('manager.getOrderHistory') }}");

        getOrders.then(function (response) {
          if (response.status === 200) {
            self.orders = response.data.orders;
          } else {

          }
        })

      }
    });
  </script>
@endpush
