<template>
    <div class="mini-basket" >
        <span class="mini-basket__count">{{ basket.total.items.length }}</span>
        <i class="fas fa-basket"></i>
        <span class="mini-basket__total">£{{ basket.total.toFixed(2) }}</span>
    </div>
</template>


<script>
    export default {
        data() {
            return {

            }
        },
        mounted() {
        //   var self = this;
        //   var getExistingBasket = axios.get("{{ route('restaurants.resumeBasket') }}",{
        //     params : {
        //       restaurant_id : self.restaurant.id
        //     }
        //   });
        //   getExistingBasket.then(function (response) {
        //     if (response.status === 200) {
        //       self.customerOrderItems = response.data.customerOrderItems;
        //       self.fulfilmentMethod = response.data.fulfilmentMethod
        //     } else {
        //
        //     }
        //   })

        }
    }
</script>
