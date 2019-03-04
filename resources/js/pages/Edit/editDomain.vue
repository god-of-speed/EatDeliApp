<template>
    <div>
        <b-container>
        <h5 class="logo">
            <i class="fa fa-circle text-gray" />
            update domain
            <i class="fa fa-circle text-warning" />
        </h5>
        <Widget class="mx-auto" customHeader>
        <form class="mt" @submit.prevent="editDomain()">
            <Alert :message="error" :type="alertType"/>
            <!-- <div id="majorForm"> -->
                <div class="row">
                    <div id="input-fields" class="col-lg-7 col-md-10 col-sm-10">
                        <div class="form-group">
                            <label for="brandname">Brand Name</label>
                            <input type="text" id="brandname" placeholder="Brand" class="form-control mb-2" v-model="brandname">
                            <small class="form-text text-muted">Brand name must be unique.</small>
                        </div>
                        <div class="form-group">
                            <label for="tags">Tags</label>
                            <vue-tags-input v-model="tag" :tags="tags" @tags-changed="newTags => tags = newTags"/>
                            <small class="form-text text-muted">press the enter or return key after each tag.</small>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea id="description" class="form-control mb-10" v-model="description"></textarea>
                            <small class="form-text text-muted">Describe your business.</small>
                        </div>
                    </div>
                </div>
            <!-- </div> -->
            <div class="d-flex flex-row justify-content-center align-items-center">
                <button @click.prevent="editDomain()" type="submit" class="btn btn-sm btn-success">Update Domain</button>
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
            required:true
        }
    },
    components: {
        VueTagsInput,
        Widget
    },
    data() {
        return {
            error:null,
            brandname:"",
            description:"",
            tags: [],
            tag:""
        }
    },
    methods:{
        editDomain() {
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
            form.append('brandname',this.brandname);
            form.append('tags',tags);
            form.append('description',this.description);

            //submit data
            axios.post(`/api/domain/update/${this.domain}`,form)
            .then((res)=> {
                this.isLoading = false;
                this.$router.push({name:'domain.show',params:{domain:res.data.data.domain.id}});
            })
            .catch((err) => {
                this.isLoading = false;
                this.alertType = "danger";
                this.error = err.response.data;
            });
        }
    },
    created() {
        //get domain
        this.isLoading = true;
        axios.get(`/api/domain/edit/${this.domain}`)
        .then((res) =>  {
            this.isLoading = false;
            this.brandname = res.data.data.domain.brandname;
            this.description = res.data.data.domain.description;
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