<template>
    <div>
        <div v-if="!isLoading">
            <h6 class="page-title">Welcome <small>{{ username }}</small></h6><br>
        <b-row>
            <b-col lg="12" sm="12" xs="12">
                <b-card-group deck>
                    <b-card v-for="product in products" v-bind:key="product.id" no-body style="min-width: 13rem;margin: 5px 8px 8px 5px;max-width: 13rem;">
                        <b-img class="card-image" thumbnail fluid :src="product.image" img-alt="Image"/>
                        <div class="card-body">
                            <router-link class="filter-product" :to="{name:'product.index', params:{product: product.id}}">
                                <h5 class="card-title"><small>{{ product.name }}</small></h5>
                            </router-link>
                            <div class="price">
                                <span><strong>Price: </strong>&#8358;{{ product.price }}</span>
                                <span v-if="getOldPrice(product)" style="float:right;"><strong>before: </strong><span style="text-decoration:line-through;">&#8358;{{ getOldPrice(product) }}</span></span>
                            </div>
                            <button class="btn btn-block btn-sm btn-success" @click="addToCart(product)">Add to cart <span class="pull-right">{{ `${inCart(product.id)}` }}</span></button>
                            <button class="btn btn-block btn-sm btn-primary" @click="order(product.id)">Order</button>
                        </div>
                    </b-card>
                </b-card-group>
            </b-col>
        </b-row>
        <div v-if="products.length > 0" class="mt-3 text-center">
            <b-pagination align="center" :total-rows="totalRows" v-model="currentPage" :per-page="20" />
        </div>
        </div>
        <div v-if="isLoading" class="loader d-flex flex-row align-items-center justify-content-center">
            <loader/>
        </div>
    </div>
</template>

<script>
import Loader from  "../../components/mixins/loaderMixin.vue";
import ProductMixin from "../../components/mixins/productMixin"; 
export default {
    name: 'Dashboard',
    mixins:[ProductMixin,Loader],
    data() {
      return {
        error:null,
        products:[],
        currentPage:null,
        totalRows:null
      }
     
    },
    computed: {
      username() {
        let user = this.$store.getters['security/getUser'];
        if(user) {
          return user.username;
        }
        return "";
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
        this.isLoading = true;
      axios.get('/api/home/index')
      .then((res) => {
            this.isLoading = false;
            this.error = null;
            if(res.data.products) {
                this.products = res.data.products.data;
                //pagination
                this.totalRows = res.data.products.total;
                this.currentPage = res.data.products.current_page;
            }
      })
      .catch((err) => {
          this.isLoading = false;
          this.error = err.response.data;
      });
  }
};
</script>

<style src="./dashboard.scss" lang="scss"></style>
<style scoped>
.card-image {
        height:200px !important;
        width:100% !important;
    }
.filter-product {
    margin:0;
}
.filter-product:hover {
    color:green;
    text-decoration:none;
}
</style>