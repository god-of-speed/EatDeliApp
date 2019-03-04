<template>
    <div>
        <b-nav>
            <b-nav-item>
                <h5><small>{{ title }}</small></h5>
            </b-nav-item>
            <b-nav-item>
                <b-button @click.prevent="getMenuCategory(menu)" size="sm" variant="outline-primary">filter</b-button>
                <b-button v-if="!displayForm && isAdmin" @click.prevent="addCategory()" size="sm" variant="outline-primary">add category</b-button>
                <b-button v-if="isAdmin" :to="{name:'register.product',params:{domain:domain},query:{menu:menu}}" @click.prevent="addProduct()" size="sm" variant="outline-primary">add product</b-button> 
                <b-button v-if="isAdmin && !editForm" @click.prevent="editMenu()" size="sm" variant="outline-primary">Edit</b-button>
                <b-button v-if="(displayForm || editForm) && isAdmin" @click.prevent="removeCategory()" size="sm" variant="outline-danger">Hide</b-button>
            </b-nav-item>
        </b-nav>
        <b-row>
            <b-col>
                <div>
                    <b-row id="minor" v-if="minorShow">
                        <Widget id="filter-widget" class="mx-auto" customHeader>
                            <div class="d-flex flex-row justify-content-start align-items-center">
                                <b-link class="filter-link" v-for="menuClass in menuClasses" v-bind:key="menuClass.id" :to="{name:'menuClass.index',params:{domain:domain,menu:menuClass.menu_id,menuClass:menuClass.id}}"><small>{{ menuClass.title }}</small></b-link>
                            </div>
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
                    <div v-if="products.length > 0" class="mt-3 text-center">
                        <b-pagination align="center" :total-rows="totalRows" v-model="currentPage" :per-page="20" />
                    </div>
                </b-row>
            </b-col>
            <b-col v-if="displayForm" lg="3">
                <h5 class="logo">
                    <i class="fa fa-circle text-gray" />
                    add a category
                    <i class="fa fa-circle text-warning" />
                </h5>
                <Widget class="mx-auto" customHeader>
                    <b-form v-if="displayForm" id="dropdown-form">
                        <b-form-group label="Name" label-for="name">
                            <b-form-input size="sm" placeholder="breakfast" id="name" v-model="categoryName"></b-form-input>
                        </b-form-group>
                        <b-form-group label="Description" label-for="description">
                            <b-form-textarea size="sm" rows="3" placeholder="" id="name" v-model="categoryDescription"></b-form-textarea>
                        </b-form-group>
                        <div class="d-flex flex-row justify-content-center align-items-center">
                            <b-button variant="primary" size="sm" @click.prevent="addCategoryAction()">add category</b-button>
                        </div>
                    </b-form>
                </Widget>
            </b-col>
            <b-col v-if="editForm" lg="3">
                <h5 class="logo">
                    <i class="fa fa-circle text-gray" />
                    Update menu
                    <i class="fa fa-circle text-warning" />
                </h5>
                <Widget class="mx-auto" customHeader>
                    <b-form v-if="editForm" id="dropdown-form">
                        <div class="form-group">
                            <label for="menuname">Name</label>
                            <input type="text" id="menuname" placeholder="African Dishes" class="form-control mb-2" v-model="menuname">
                            <small class="form-text text-muted"></small>
                        </div>
                        <div class="form-group">
                            <label for="menuDescription">Description</label>
                            <textarea type="text" id="menuDescription" class="form-control mb-2" v-model="menuDescription"></textarea>
                            <small class="form-text text-muted"></small>
                        </div>
                        <div class="d-flex flex-row justify-content-center align-items-center">
                            <b-button variant="primary" size="sm" @click.prevent="editMenuAction()">update menu</b-button>
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
                displayForm:false,
                error:null,
                title:"",
                description:"",
                categoryName:"",
                categoryDescription:"",
                isAdmin: false,
                products: [],
                totalRows: null,
                currentPage: null,
                menuClasses:null,
                menuname:"",
                menuDescription:""
            }
        },
        computed: {
            hasError() {
                return this.error;
            }
        },
        methods: {
            editMenu() {
                let [clientWidth,clientHeight] = this.$store.getters['layout/getClientProperties'];
                if(clientWidth > 760 && clientHeight > 760) {
                    this.menuname = this.title;
                    this.menuDescription = this.description;
                    return this.editForm = true;
                }else{
                    return this.$router.push({name:'edit.menu',params:{domain:this.domain,menu:this.menu}});
                }
            },
            editMenuAction() {
                //update menu
                let form =  new FormData();
                form.append('title',this.menuname);
                form.append('description',this.menuDescription);
                this.isLoading = true;

                axios.post(`/api/menu/update/${this.menu}`,form)
                .then((res) => {
                    this.isLoading = false;
                    //reset menu details
                    this.title = res.data.data.menu.title;
                    this.description = res.data.data.menu.description;
                    this.editForm = false;
                })
                .catch((err) => {
                    this.isLoading = false;
                    this.alertType="danger"
                    this.error = err.response.data;
                });
            },
            getOldPrice(product) {
                if(product.oldPrice < product.price) {
                  return product.oldPrice;
                }
                return null;
            },
            removeCategory() {
                this.displayForm = false;
                this.editForm = false;
                return true;
            },
            addCategory() {
                let [clientWidth,clientHeight] = this.$store.getters['layout/getClientProperties'];
                if(clientWidth > 760 && clientHeight > 760) {
                    return this.displayForm = true;
                }else{
                    return this.$router.push({name:'register.menuClass',params:{domain:this.domain,menu:this.menu}});
                }
            }, 
            addCategoryAction() {
                //get form data
                var form = new FormData();
                form.append('title',this.categoryName);
                form.append('description',this.categoryDescription);
                this.isLoading = true;

                //send data
                axios.post(`/api/menu-class/store/${this.domain}`,form)
                .then((res) => {
                    this.isLoading = false;
                    this.error = "";
                    this.$router.push({name:'menuClass.index', params: {domain:this.domain,menu:this.menu,menuClass: res.data.data.menuClass.id}});
                })
                .catch((err) => {
                    this.isLoading = false;
                    this.alertType = "danger";
                    this.error = err.response.data;
                });
            },
            getMenuCategory(menu) {
                if(this.minorShow) {
                    return this.minorShow = false;
                }
                //get domain menus
                this.isLoading = true;
                axios.get(`/api/menu/menu-class/${menu}`)
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
                return this.minorShow = true;
            }
        },
        created() {
            this.isLoading = true;
            axios.get(`/api/menu/index/${this.menu}`)
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
                this.title = res.data.data.menu.title;
                this.description = res.data.data.menu.description;
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

#minor {
    margin:5px 0px 5px 0px;
    padding:5px;
    width:100%;
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
#dropdown-form {
    padding:7px;
}
#filter-widget {
    width: 98%;
    padding: 10px !important;
    margin-left:0px !important;
    background-color:#ddd !important;

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