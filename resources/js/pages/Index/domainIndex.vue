<template>
    <div>
        <div class="d-flex flex-row justify-content-start align-items-center">
            <b-button @click.prevent="changeMinorShow()" size="sm" variant="outline-primary">filter</b-button>
            <b-link class="filter-link" :to="{name:'domain.show'}">about</b-link> 
        </div>
        <div>
            <b-row id="minor" v-if="minorShow">
                <Widget class="mx-auto" customHeader>
                    <div class="d-flex flex-row justify-content-start align-items-center">
                        <b-button variant="primary" class="filter-link-btn" @click="getDomainMenu(domain)"><small>menu</small></b-button>
                        <b-button variant="primary" class="filter-link-btn" @click="getDomainCategory(domain)"><small>Category</small></b-button>
                        <b-button v-if="majorMinorShow" class="filter-link-btn" @click.prevent="removeMajorMinorShow()" size="sm" variant="outline-danger">remove</b-button>
                    </div>
                    <b-row id="majorMinor" v-if="majorMinorShow">
                        <div class="d-flex flex-row justify-content-start align-items-center">
                            <b-link class="filter-link" v-for="menu in menus" v-bind:key="menu.id" :to="{name:'menu.index',params:{domain:domain,menu:menu.id}}"><small>{{ menu.title }}</small></b-link>
                            <b-link class="filter-link" v-for="menuClass in menuClasses" v-bind:key="menuClass.id" :to="{name:'menuClass.index',params:{domain:domain,menu:menuClass.menu_id,menuClass:menuClass.id}}"><small>{{ menuClass.title }}</small></b-link>
                        </div>
                    </b-row>
                </Widget>
            </b-row>
        </div>
        <b-row id="major">
            <b-col lg="12" sm="12" xs="12">
                <Alert :message="error" :type="alertType"/>
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
            domain: {
                required:true
            }
        },
        mixins:[ProductMixin,Loader,Alert],
        components: {
            Widget
        },
        data() {
            return {
                majorMinorShow:false,
                minorShow:false,
                error:null,
                products: [],
                totalRows: null,
                currentPage: null,
                menus:null,
                menuClasses:null
            }
        },
        computed: {
            hasError() {
                return this.error;
            },
        },
        methods: {
            getOldPrice(product) {
                if(product.oldPrice < product.price) {
                  return product.oldPrice;
                }
                return null;
            },
            changeMinorShow() {
                if(this.minorShow) {
                    return this.minorShow = false;
                }
                return this.minorShow = true;
            },
            removeMajorMinorShow() {
                return this.majorMinorShow = false;
            },
            getDomainMenu(domain) {
                this.menuClasses = null;
                this.majorMinorShow = true;
                //get domain menus
                this.isLoading = true;
                axios.get(`/api/domain/menu/${domain}`)
                .then((res) => {
                    this.isLoading = false;
                    if(res.data.data.menus) {
                        this.menus = res.data.data.menus;
                    }
                })
                .catch((err) => {
                    this.isLoading = false;
                    this.alertType = "danger";
                    this.error = err.response.data;
                });
            },
            getDomainCategory(domain) {
                this.menus = null;
                this.majorMinorShow = true;
                //get domain menus
                this.isLoading = true;
                axios.get(`/api/domain/menuClass/${domain}`)
                .then((res) => {
                    this.isLoading = false;
                    if(res.data.data.menuClasses) {
                        this.menuClasses = res.data.data.menuClasses;
                    }
                })
                .catch((err) => {
                    this.isLoading = false;
                    this.alertType = "danger";
                    this.error = err.response.data;
                });
            }
        },
        created() {
            this.isLoading = true;
            axios.get(`/api/domain/index/${this.domain}`)
            .then((res) => {
                this.isLoading = false;
                this.error = "";
                if(res.data.data.products) {
                    this.products = res.data.data.products.data;
                    //links
                    this.totalRows = res.data.data.products.total;
                    this.currentPage = res.data.data.products.current_page;
                }
            })
            .catch((err) => {
                this.isLoading = false;
                this.alertType = "danger";
                this.error = "invalid data.";
            });
        }
    }
</script>
<style lang="scss" scoped>
    @import '../../styles/app';

    #ddown {
        margin-bottom: 5px;
     }
    #major {
        padding:5px;
    }
    #checkRed    {
        border:1px solid red;
    }
    #minor {
        margin:5px 0px 5px 0px;
        padding:5px;
        width:90%;
    }
    #majorMinor {
        margin:5px 0px 5px 0px;
        padding:5px;
        width:100%;
        background-color: #ddd;
    }
    .filter-link-btn {
        margin:0px 4px 5px 4px;
        padding:1px 4px 1px 4px;
    }
    .filter-link-btn:hover {
        color:white;
        text-decoration:none;
    }
    .filter-product {
        margin:0;
    }
    .filter-product:hover {
        color:green;
        text-decoration:none;
    }
    .filter-link {
        margin:0px 4px 5px 4px;
    }
    .filter-link:hover {
        color:green;
        text-decoration:none;
    }
    .card-image {
        height:200px !important;
        width:100% !important;
    }
    .widget {
    width: 100%;
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