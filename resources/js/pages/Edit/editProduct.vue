<template>
<div>
    <b-container>
        <h5 class="logo">
            <i class="fa fa-circle text-gray" />
            Update product
            <i class="fa fa-circle text-warning" />
        </h5>
        <Widget class="mx-auto" customHeader>
        <form class="mt" @submit.prevent="editProduct()">
            <Alert :message="error" :type="alertType"/>
            <!-- <div id="majorForm"> -->
                <div class="row">
                    <div id="input-fields" class="col-lg-7 col-md-10 col-sm-10">
                        <div class="form-group">
                            <label for="brandname">Name</label>
                            <input type="text" id="name" placeholder="amala" class="form-control mb-2" v-model="name">
                            <small class="form-text text-muted"></small>
                        </div>
                        <div class="form-group">
                            <label for="tags">Tags</label>
                            <vue-tags-input v-model="tag" :tags="tags" @tags-changed="newTags => tags = newTags"/>
                            <small class="form-text text-muted">press the enter or return key after each tag.</small>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea id="description" class="form-control mb-10" v-model="description"></textarea>
                            <small class="form-text text-muted">Describe the meal.</small>
                        </div>
                    </div>
                </div>
            <!-- </div> -->
            <div class="d-flex flex-row justify-content-center align-items-center">
                <button @click.prevent="editProduct()" type="submit" class="btn btn-sm btn-success">Update Product</button>
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
import VueTagsInput from '@johmun/vue-tags-input';
import Widget from '../../components/Widget/Widget';
import Loader from  "../../components/mixins/loaderMixin.vue";
import Alert from  "../../components/mixins/alertMixin.vue";

export default {
    mixins:[Loader,Alert],
    props:{
        product:{
            required: true
        }
    },
    components: {
        VueTagsInput,
        Widget
    },
    data() {
        return {
            error:null,
            name: "",
            tags: [],
            tag:"",
            description: "",
        }
    },
    methods:{
        editProduct() {
            this.isLoading = true;
            let tags = this.tags.reduce((tagString,current,index,array) => {
                if(index != array.length-1) {
                    tagString += `${current.text},`;
                }else{
                    tagString += current.text;
                }
                return tagString;
            },"");

            let form = new FormData();
            form.append('name',this.name);
            form.append('tags',tags);
            form.append('description',this.description);

            //submit data
            axios.post(`/api/product/update/${this.product}`,form)
            .then((res)=> {
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
        //get domain
        axios.get(`/api/product/edit/${this.product}`)
        .then((res) =>  {
            this.isLoading = false;
            this.name = res.data.data.product.name;
            this.description = res.data.data.product.description;
            this.currency = res.data.data.product.currency;
            this.price = res.data.data.product.price;
            let tags = res.data.data.tags;
            //populate the tags input
            tags.forEach((element)  =>  {
                return this.tags.push({text:element.tag});
            });
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
    padding: 20px !important;

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