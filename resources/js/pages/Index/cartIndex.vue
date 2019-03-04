<template>
    <div>
        <b-container>
            <Widget class="mx-auto" customHeader>
                <Alert :message="error" :type="alertType"/>
                <div v-if="carts.length == 0" class="d-flex flex-row justify-content-center align-items-center">
                    <small><strong>Cart is empty.</strong></small>
                </div>
                <b-row class="holder" v-for="cart in carts" v-bind:key="cart.index">
                    <b-col lg="2" md="10" sm="10">
                        <div class="d-flex flex-row justify-content-center align-items-center">
                            <b-img v-bind="mainProps" id="image-holder" :src="cart.product.image" rounded="top" alt="Top-rounded image"/>
                        </div>
                    </b-col>
                    <b-col lg="9" md="10" sm="10">
                        <strong>{{ cart.product.name }}</strong><br>
                        <span><strong>Quantity</strong>: x{{ cart.quantity }}</span><br>
                        <span><strong>per 1</strong>: &#8358;{{ cart.product.price }}</span><br>
                        <span><strong>Price</strong>: &#8358;<span ref="prices">{{ getProductPrice(cart) }}</span></span><br>
                        <b-button size="sm" variant="primary" @click.prevent="addToCart(cart.product)">+</b-button>
                        <b-button size="sm" variant="primary" @click.prevent="removeFromCart(cart.product)">-</b-button>
                    </b-col>
                </b-row>
                <b-row>
                    <b-col v-if="carts.length > 0" lg="12" md="12" sm="12">
                        <div class="offset-9 align-items-bottom">
                            <span><strong>Items</strong>: {{ getTotalItems }}</span><br>
                            <span><strong>Total amount</strong>: &#8358;{{ getTotalAmount }}</span><br>
                            <b-button size="sm" variant="primary" @click.prevent="orderAll()">Order now</b-button>
                        </div>
                    </b-col>
                </b-row>
            </Widget>
        </b-container>
        <div v-if="isLoading" class="loader d-flex flex-row align-items-center justify-content-center">
            <loader/>
        </div>
    </div>
</template>
<script>
import Loader from  "../../components/mixins/loaderMixin.vue";
import Widget from '../../components/Widget/Widget';
import Alert from  "../../components/mixins/alertMixin.vue";
export default {
    mixins:[Loader,Alert],
    components:{
        Widget
    },
    data() {
        return {
            carts: null,
            error: null,
            mainProps: { width: 70, height: 70, class: 'm1' }
        }
    },
    computed: {
        getTotalItems() {
            //get total items in the cart array
            return this.carts.length;
        },
        getTotalAmount() {
            return this.carts.reduce((total,current,index,array) => {
                return total += parseInt(current.product.price) * parseInt(current.quantity);
            },0); 
        }
    },
    methods: {
        getProductPrice(cart) {
            //get product price
            let price = cart.product.price;
            let result = parseInt(price) * parseInt(cart.quantity);
            return result.toFixed(2);
        },
        addToCart(product) {
            //add ato cart
            this.$store.dispatch('cart/addToCart',product);
        },
        removeFromCart(product) {
            //remove from cart
            this.$store.dispatch('cart/removeFromCart',product);
        },
        orderAll() {
            let cart = JSON.stringify(this.carts);
            //order for everything in the cart
            this.isLoading = true;
            axios.post('/api/order/store',cart,{
                headers: {
                    'Content-Type': 'application/json',
                }
            })
            .then((res) => {
                this.isLoading = false;
                this.$store.dispatch('cart/removeAll');
                this.$router.push({name:"order.index"});
            })
            .catch((err) => {
                this.isLoading = false;
                this.alertType = "danger";
                this.error = err.response.data;
            });
        }
    },
    created() {
        this.carts = this.$store.getters['cart/getCart'];
    }
}
</script>
<style lang="scss" scoped>
@import '../../styles/app';
.holder{
    margin:10px;
    padding:5px;
}

.widget {
    max-width: 100%;
    padding: 10px !important;
    margin-left:0px !important;

    h1,
    h2,
    h3,
    h4,
    h5,
    h6 {
      font-weight: $font-weight-normal;
    }
  }
</style>