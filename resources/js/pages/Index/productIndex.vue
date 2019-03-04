<template>
    <div>
        <b-container>
            <Widget class="mx-auto" customHeader>
                <Alert :message="error" :type="alertType"/>
                <b-row>
                    <b-col lg="5" md="10" sm="10">
                        <div class="d-flex flex-row justify-content-center align-items-center">
                            <div class="form-group">
                                <b-img v-bind="mainProps" id="image-holder" ref="brandImageHolder" :src="productDetails.image" rounded="top" alt="Top-rounded image" />
                            </div>
                        </div>
                    </b-col>
                    <b-col lg="7" md="10" sm="10">
                        <h5><strong>{{ productDetails.name }}</strong></h5><br>
                        <span><small><strong>price</strong></small>: <small>&#8358;{{ productDetails.price }}</small></span>
                        <span v-if="getOldPrice(productDetails)"><small><strong>before</strong></small>:<span style="text-decoration:line-through;"><small>&#8358;{{ getOldPrice(productDetails) }}</small></span></span><br>
                        <span><small><strong>description</strong></small>:</span>
                        <span><small>{{ productDetails.description }}</small></span><br><br>
                        <b-button size="sm" variant="success" @click="addToCart(productDetails)">Add to cart <span class="pull-right">{{ ` ${inCart(productDetails.id)}` }}</span></b-button>
                        <b-button size="sm" variant="primary" @click="order(productDetails.id)">Order</b-button><br><br>
                        <b-button v-if="isAdmin" size="sm">Update</b-button>
                        <b-button v-if="isAdmin" size="sm" :to="{name:'edit.product',params:{product:productDetails.id}}">Edit</b-button>
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
import ProductMixin from "../../components/mixins/productMixin";
import Alert from  "../../components/mixins/alertMixin.vue"; 

export default {
    props:{
        product: {
            required:true
        }
    },
    mixins:[ProductMixin,Loader,Alert],
    components: {
        Widget
    },
    data() {
        return {
            mainProps: { width: 250, height: 250, class: 'm1' },
            error:null,
            productDetails:{image:"",id:"",name:""},
            isAdmin:false
        }
    },
    methods: {
        getOldPrice(product) {
            if(product.oldPrice < product.price) {
              return product.oldPrice;
            }
            return null;
        }
    },
    created() {
        //get product
        this.isLoading = true;
        axios.get(`/api/product/index/${this.product}`)
        .then((res) => {
            this.isLoading = false;
            this.error = null;
            this.productDetails = res.data.data.product;
            this.isAdmin = res.data.data.isAdmin;
        })
        .catch((err) => {
            this.isLoading = false;
            this.alertType = "danger";
            this.error = err.response.data;
        });
    }
}
</script>
<style lang="scss" scoped>
@import '../../styles/app';

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