<div class="mini-basket" v-bind:class="(itemsCount >= 1) ? 'mini-basket--loaded' : '' " @click="checkoutAlias">
    <span class="mini-basket__count">@{{ itemsCount }}</span>
    <i class="fas fa-shopping-basket"></i>
    <span class="mini-basket__total">£@{{ total.toFixed(2) }}</span>
</div>

<script>
    window.addEventListener('load', function() {
        setTimeout(function () {
        const miniBasket = new Vue({
            el: '.mini-basket',
            data: {
                itemsCount: (typeof createOrder != "undefined") ? createOrder.customerOrder.items.length : 0,
                total: (typeof createOrder != "undefined") ? createOrder.customerOrder.total : 0
            },
            methods: {
                checkoutAlias : function () {
                    createOrder.checkout();
                }
            }
        });
        }, 1000);
    });
</script>
