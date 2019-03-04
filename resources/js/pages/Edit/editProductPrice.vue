<template>
<div>
    <b-container>
        <h5 class="logo">
            <i class="fa fa-circle text-gray" />
            Update product price
            <i class="fa fa-circle text-warning" />
        </h5>
        <Widget class="mx-auto" customHeader>
            <form class="mt" @submit.prevent="updateProduct()">
                <Alert :message="error" :type="alertType"/>
                <b-row>
                    <b-col lg="3" md="12" sm="12">
                        <div class="form-group">
                            <label for="currency">Currency</label>
                            <b-form-select id="currency" v-model="currency" :options="currencyOptions"></b-form-select>
                            <small class="form-text text-muted"></small>
                        </div>
                    </b-col>
                    <b-col lg="9" md="12" sm="12">
                        <div class="form-group">
                            <label for="price">Price</label>
                            <input type="text" id="price" placeholder="200-500" class="form-control mb-2" v-model="price">
                            <small class="form-text text-muted">place a hyphen between range figures</small>
                        </div>
                    </b-col>
                </b-row>
                <div class="d-flex flex-row justify-content-center align-items-center">
                    <button @click.prevent="updateProduct()" type="submit" class="btn btn-sm btn-success">update</button>
                </div>
            </form>
        </Widget>
    </b-container>
    <div v-if="isLoading" class="loader d-flex flex-row align-items-center justify-content-center">
        <loader/>
    </div>
</div>
</template>
<script>
import Widget from '../../components/Widget/Widget';
import Loader from  "../../components/mixins/loaderMixin.vue";
import Alert from  "../../components/mixins/alertMixin.vue";

export default {
    mixins:[Loader,Alert],
    props:{
        product:{
            required:true
        }
    },
    components:{
        Widget
    },
    data() {
        return {
            currency:null,
            currencyOptions:[
                { value:null, text: 'select'},
                { value:'naira',text:'Naira'},
                { value:'dollars',text:'Dollars'}
            ],
            price:"",
            error:null
        }
    },
    methods:{
        updateProduct() {
            this.isLoading = true;
            //submit data
            let form = new FormData();
            form.append('currency',this.currency);
            form.append('price',this.price);

            axios.post(`/api/product/updatePrice/${this.price}`,form)
            .then((res) => {
                this.isLoading = false;
                this.$router.push({name:'product.index',params:{product:res.data.data.product.id}});
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
        //get product details
        axios.get(`/api/product/edit/${this.product}`)
        .then((res) => {
            this.isLoading = false;
            this.currency = res.data.data.product.currency;
            this.price = res.data.data.product.price;
        })
        .catch((err) => {
            this.isLoading = false;
            this.alertType = "danegr";
            this.error = err.response.data;
        });
    }
}
</script>
<style></style>