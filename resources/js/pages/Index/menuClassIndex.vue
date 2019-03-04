<template>
    <div>
        <b-nav>
            <b-nav-item>
                <h5><small>{{ menuClassDetails.title }}</small></h5>
            </b-nav-item>
            <b-nav-item>
                <b-button size="sm" variant="outline-primary">filter</b-button>
                <b-button v-if="isAdmin && !editForm" @click.prevent="editCategory()" size="sm" variant="outline-primary">Edit</b-button>
                <b-button v-if="editForm && isAdmin" @click.prevent="removeCategory()" size="sm" variant="outline-danger">Hide</b-button>
                <b-button v-if="isAdmin" :to="{name:'register.product',params:{domain:domain},query:{menu:menu,menuClass:menuClass}}" @click.prevent="addProduct()" size="sm" variant="outline-primary">add product</b-button> 
            </b-nav-item>
        </b-nav>
        <b-row>
            <b-col>
                <div id="minor" v-show="minorShow">
                    <div class="d-flex flex-row justify-content-start align-items-center">
                
                    </div>
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
                    <div v-if="products.length > 0" class="mt-3 text-center">
                        <b-pagination align="center" :total-rows="totalRows" v-model="currentPage" :per-page="20" />
                    </div>
                </b-row>
            </b-col>
            <b-col v-if="editForm" lg="3">
                <h5 class="logo">
                    <i class="fa fa-circle text-gray" />
                    Update category
                    <i class="fa fa-circle text-warning" />
                </h5>
                <Widget class="mx-auto" customHeader>
                    <b-form v-if="editForm" id="dropdown-form">
                        <div class="form-group">
                            <label for="menuname">Name</label>
                            <input type="text" id="menuname" placeholder="appetizer" class="form-control mb-2" v-model="name">
                            <small class="form-text text-muted"></small>
                        </div>
                        <div class="form-group">
                            <label for="menuDescription">Description</label>
                            <textarea type="text" id="menuDescription" class="form-control mb-2" v-model="description"></textarea>
                            <small class="form-text text-muted"></small>
                        </div>
                        <div class="d-flex flex-row justify-content-center align-items-center">
                            <b-button variant="primary" size="sm" @click.prevent="editCategoryAction()">update category</b-button>
                        </div>
                    </b-form>
                </Widget>
            </b-col>
        </b-row>
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
            },
            menu: {
                required:true
            },
            menuClass: {
                required:true
            }
        },
        mixins:[ProductMixin,Loader,Alert],
        components: {
            Widget
        },
        data() {
            return {
                editForm:false,
                minorShow:false,
                error:null,
                menuClassDetails:{title:""},
                isAdmin: false,
                name:"",
                description:"",
                products: [],
                totalRows: null,
                currentPage: null
            }
        },
        computed: {
            hasError() {
                return this.error;
            }
        },
        methods: {
            editCategory() {
                let [clientWidth,clientHeight] = this.$store.getters['layout/getClientProperties'];
                if(clientWidth > 760 && clientHeight > 760) {
                    this.name = this.menuClassDetails.title;
                    this.description = this.menuClassDetails.description;
                    return this.editForm = true;
                }else{
                    return this.$router.push({name:'edit.menuClass',params:{domain:this.domain,menu:this.menu,menuClass:this.menuClass}});
                }
            },
            editCategoryAction() {
                //submit data
                let form = new FormData();
                form.append('title',this.name);
                form.append('description',this.description);
                this.isLoading = true;

                axios.post(`/api/menu-class/update/${this.menuClass}`,form)
                .then((res) => {
                    this.isLoading = false;
                    this.menuDetails = res.data.data.menu;
                    this.editForm = false;
                })
                .catch((err) => {
                    this.isLoading = false;
                    this.alertType = "danger";
                    this.error = err.response.data;
                });
            },
            getOldPrice(product) {
                if(product.oldPrice < product.price) {
                    return product.oldPrice;
                }
                return null;
            }
        },
        created() {
            this.isLoading = true;
            axios.get(`/api/menu-class/index/${this.menuClass}`)
            .then((res) => {
                this.isLoading = false;
                this.error = "";
                if(res.data.data.products.data) {
                    this.products = res.data.data.products.data;
                    //links
                    this.totalRows = res.data.data.products.total;
                    this.currentPage = res.data.data.products.current_page;
                }

                this.isAdmin = res.data.data.isAdmin;
                this.menuClassDetails = res.data.data.menuClass;
            })
            .catch((err) => {
                this.isLoading = false;
                this.alertType = "danger";
                this.error = "invalid data.";
            });
        }
}
</script>
<style scoped>
#minor {
    margin:5px 0px 5px 0px;
    padding:5px;
    width:90%;
}
.filter-link {
    margin:0px 4px 5px 4px;
}
.filter-link:hover {
    color:green;
    text-decoration:none;
}
.filter-product {
    margin:0;
}
.filter-product:hover {
    color:green;
    text-decoration:none;
}
.card-image {
    height:200px !important;
    width:100% !important;
}
</style>