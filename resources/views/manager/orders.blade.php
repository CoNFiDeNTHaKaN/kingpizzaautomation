@extends('templates.manager')

@section('pageTitle', 'Live orders')

@push('bodyClasses') live-orders @endpush

@section('content')
  <div class="wrapper">
    <h1>Eat Kebab Online live orders</h1>

    <div id="live-orders" v-bind:class="[orderToConfirm ? 'live-orders--order-being-confirmed' : '']">

      <div class="row live-order" v-for="order in orders" v-bind:class="[ order == orderToConfirm ? 'live-order--being-confirmed' : '']">
        <div class="live-order__details col-12 col-sm-9">
          <div class="live-order__details__headlines flex flex--v-bottom flex--spread">
            <span>@{{ order['user']['name'] }} (#@{{ order['id'] }})</span>
            <span>Placed at @{{ order['created_at'] | date('HH:mm') }}<sup><b>&nbsp;(@{{ order['created_at'] | timeAgo() }})</b></sup></span>
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
          <div class="live-order__details__items">
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

        <div class="live-order__delivery col-12 col-sm-3">
          <div class="live-order__deliver__time t-right">
            <u>@{{ order['fulfilment_method'] }} time</u><br>
                <template v-if="order['predicted_time']">
                  <span><b>@{{ order['predicted_time'] | date('HH:mm') }}</b></span>
                  (prediction)
                </template>
                <template v-else>
                  <span><b>@{{ order['desired_time'] | date('HH:mm') }}</b></span>
                  <span data-tip="This is the time the user has requested their order for, please confirm if this is possible or send an update if not.">(requested)</span>
                </template>
          </div>
          <div class="live-order__deliver__address">
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
        <div class="live-order__actions col-12 flex flex--v-center flex--spread">
          <span @click="cancelOrder(order)" class="button button--red">Cancel order</span>

          <template v-if="order['predicted_time'] === null">
            <span @click="openOrderConfirmModal(order)" class="button button--green">Confirm order</span>
          </template>
          <template v-else>
            <!--<span class="button button--green-hollow">Change status</span>-->
            <!--<span class="button button--green">Send update</span>-->
            <p><b>Contact customer</b>: @{{ order['user']['contact_number'] }}</p>
          </template>
        </div>
      </div>

      <div v-if="orders.length == 0" >
        <br>
        <br>
        <h2 class="t-center">No new orders in the last 24 hours.</h2>
        <p class="t-center">For older orders check the <b><a href="{{ route('manager.orderHistory') }}">order history</a></b>.</p>
        <br>
        <br>
        <br>
      </div>

    <div class="fs-overlay" v-if="modalOpen === true">

    </div>
    <div class="confirm-order" v-if="confirmingOrder === true" @click.self="cancelOrderConfirmModal()">
      <div class="confirm-order--modal modal">
        <h2>Confirm estimated @{{ orderToConfirm['fulfilment_method'] }} time for @{{ orderToConfirm['user']['name'] }}</h2>
        <p>The customer has requested a @{{ orderToConfirm['fulfilment_method'] }} time of <b>@{{ orderToConfirm['desired_time'] | date('HH:mm') }} (@{{ orderToConfirm['desired_time'] | fromNow }} from now)</b></p>
        <form action="" class="flex flex--v-top flex--align-center" onsubmit="return false;">
          <div class="formitem">
            <input type="number" min="0" step="1" max="23" name="hours" placeholder="HH" v-model="confirmHour" @blur="addLeadingZero()">
          </div>
          <div class="formitem">
            <input type="number" min="0" step="5" max="55" name="minutes" placeholder="MM" v-model="confirmMinute" @blur="addLeadingZero()">
          </div>
          <input type="submit" value="confirm" class="button button--green" @click="confirmOrder()">
        </form>
      </div>
    </div>


    </div>
  </div>
@endsection

@push('footerScripts')
  <script>
    const manageOrders = new Vue({
      el : '#live-orders',
      data : {
        orders : [],
        confirmingOrder: false,
        orderToConfirm : false,
        modalOpen : false,
        confirmHour : 0,
        confirmMinute : 0,
      },
      methods : {
        openOrderConfirmModal : function (order) {
          this.modalOpen = true;
          this.confirmingOrder = true;
          this.orderToConfirm = order;
        },
        cancelOrderConfirmModal : function () {
          this.modalOpen = false;
          this.confirmingOrder = false;
          this.orderToConfirm = false;
        },
        confirmOrder : function () {
          var self = this;

          postConfirmation = axios.post("{{ route('manager.confirmOrder') }}", {
            order_id : this.orderToConfirm['id'],
            hour : this.confirmHour,
            minute : this.confirmMinute
          });

          postConfirmation.then(function (response) {
            if (response.status === 200) {
              self.modalOpen = false;
              self.confirmingOrder = false;
              self.orderToConfirm = false;
              self.getOrders();
            } else {

            }
          });
        },
        addLeadingZero : function () {
          var input = event.target;
          input.value = input.value.length < 2 ? "0" + input.value : input.value;
        },
        getOrders : function () {
            var self = this;
            var getOrders = axios.get("{{ route('manager.getOrders') }}");

            getOrders.then(function (response) {
              if (response.status === 200) {
                self.orders = response.data.orders;
              } else {

              }
            })
        },
        cancelOrder : function (order) {
          var self = this;

          this.cancellingOrder = true;
          this.orderToCancel = order;

          postConfirmation = axios.post("{{ route('manager.cancelOrder') }}", {
            order_id : this.orderToCancel['id']
          });
          
          postConfirmation.then(function (response) {
            if (response.status === 200) {
              self.cancellingOrder = false;
              self.orderToCancel = false;
              self.getOrders();
            } else {

            }
          });
        }

      },
      mounted : function () {
        var self = this;
        var getOrders = axios.get("{{ route('manager.getOrders') }}");

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
