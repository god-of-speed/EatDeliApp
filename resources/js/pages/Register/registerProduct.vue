<template>
<div>
    <b-container>
        <h5 class="logo">
            <i class="fa fa-circle text-gray" />
            Create a product
            <i class="fa fa-circle text-warning" />
        </h5>
        <Widget class="mx-auto" customHeader>
        <form class="mt" @submit.prevent="createProduct()">
            <Alert :message="error" :type="alertType"/>
            <!-- <div id="majorForm"> -->
                <div class="row">
                    <div class="col-lg-5 col-md-10 col-sm-10">
                        <div class="d-flex flex-row justify-content-center align-items-center">
                            <div class="form-group">
                                <label for="brand-image">
                                    <b-img v-bind="mainProps" id="brand-image-holder" ref="brandImageHolder" src="" rounded="top" alt="Top-rounded image" />
                                    <small class="form-text text-muted">click to choose product image.</small>
                                </label>
                                <input ref="image" class="visually-hidden" type="file" id="brand-image" @change.prevent="changeImageAction($event)">
                            </div>
                        </div>
                    </div>
                    <div id="input-fields" class="col-lg-7 col-md-10 col-sm-10">
                        <div class="form-group">
                            <label for="brandname">Name</label>
                            <input type="text" id="name" placeholder="amala" class="form-control mb-2" v-model="name">
                            <small class="form-text text-muted"></small>
                        </div>
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
                <button @click.prevent="createProduct()" type="submit" class="btn btn-sm btn-success">Create Product</button>
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
        domain:{
            required: true
        }
    },
    components: {
        VueTagsInput,
        Widget
    },
    data() {
        return {
            mainProps: { blank: true, blankColor: '#777', width: 250, height: 250, class: 'm1' },
            error:null,
            name: "",
            currency:null,
            currencyOptions:[
                { value:null, text: 'select'},
                { value:'naira',text:'Naira'},
                { value:'dollars',text:'Dollars'}
            ],
            price:"",
            tags: [],
            tag:"",
            description: "",
            file: null,
        }
    },
    computed: {
        checkImage() {
            if(this.$refs.brandImage.attributes['src'] = "") {
                return true;
            }
            return false;
        }
    },
    methods: {
        changeImageAction(e) {
            var myFile = e.target.files[0];
            this.file = e.target.files[0];
            var imageType = /image\/(png|jpg|jpeg)/gi;

            //check if image is correct
            if(myFile.type.match(imageType)) {
                var reader = new FileReader();
                var imageHolder = this.$refs.brandImageHolder;
                var mainProps = this.mainProps;

                //onload
                reader.onload = function() {
                    imageHolder.attributes['src'].value = reader.result;
                    mainProps = { width: 250, height: 250, class: 'm1' };       
                }
                reader.readAsDataURL(myFile);
            }
        },
        createProduct() {
            this.isLoading = false;
            //get route query 
            let menuQuery = this.$route.query.menu;
            let menuClassQuery = this.$route.query.menuClass;
            //set post url
            let url = `/api/product/store/${this.domain}`;
            if(menuQuery) {
                url += `?menu=${menuQuery}`;
            }

            if(menuClassQuery) {
                url += `?menuClass=${menuclassQuery}`;
            }

            let tags = this.tags.reduce((tagString,current,index,array) => {
                if(index != array.length-1) {
                    tagString += `${current.text},`;
                }else{
                    tagString += current.text;
                }
                return tagString;
            },"");

            //get form data
            var form = new FormData();
            form.append('name',this.name);
            form.append('description',this.description);
            form.append('tags',tags);
            form.append('image',this.file);
            form.append('currency',this.currency);
            form.append('price',this.price);

            //send data
            axios.post(url,form)
            .then((res) => {
                this.isLoading = false;
                this.error = null;
                this.$router.push({name:"product.index",params:{'product': res.data.data.product.id}});
            })
            .catch((err) => {
                this.isLoading = false;
                this.alertType = "danger";
                this.error = "invalid data.";
            });
        }
    }
}
</script>
<style src="./registerDomain.scss" lang="scss"></style>