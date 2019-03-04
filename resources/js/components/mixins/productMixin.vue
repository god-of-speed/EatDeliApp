<script>
export default {
    methods: {
        inCart(product) {
            let num = "";
            let cart = this.$store.getters['cart/getCart'];
            for(let i in cart) {
                if(cart[i].product.id == product) {
                    num = `x${cart[i].quantity}`;
                }
            }
            return num;
        },
        order(productId) {
            //check if product is in cart
            let inCart = false;
            let cart = this.$store.getters['cart/getCart'];
            let product = null;
            for(let i in cart) {
                if(cart[i].product.id == productId) {
                    inCart = true;
                    product = JSON.stringify([cart[i]]);
                }
            }
            if(inCart) {
                //order product
                axios.post('/api/order/store',product,{
                    headers: {
                        'Content-Type':'application/json'
                    }
                })
                .then((res) => {
                    //from the top unstringify the product so that it can be removed from the client cart also 
                    let cart = JSON.parse(product);
                    this.$store.dispatch('cart/orderFromCart',cart[0].product);
                })
                .catch((err) => {
                    this.error = err.response.data;
                });
            }else{
                //order product
                axios.get(`/api/product/order/${productId}`)
                .then((res) => {
                    this.error = null;
                }) 
                .catch((err) => {
                    this.error = err.response.data;
                });
            }
        },
        addToCart(product) {
            //add ato cart
            this.$store.dispatch('cart/addToCart',product);
        }
    }
}
</script>