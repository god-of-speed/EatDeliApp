<template>
    <div>
        <b-container>
            <h5 class="logo">
                <i class="fa fa-circle text-gray" />
                Update category
                <i class="fa fa-circle text-warning" />
            </h5>
            <Widget class="mx-auto" customHeader>
                <form class="mt" @submit.prevent="editCategory()">
                    <Alert :message="error" :type="alertType"/>
                    <b-row>
                        <b-col lg="12" md="12" sm="12">
                            <div class="form-group">
                                <label for="menuname">Name</label>
                                <input type="text" id="menuname" placeholder="African Dishes" class="form-control mb-2" v-model="name">
                                <small class="form-text text-muted"></small>
                            </div>
                            <div class="form-group">
                                <label for="menuDescription">Description</label>
                                <textarea type="text" id="menuDescription" class="form-control mb-2" v-model="description"></textarea>
                                <small class="form-text text-muted"></small>
                            </div>
                            <div class="d-flex flex-row justify-content-center align-items-center">
                                <b-button size="sm" variant="outline-primary" @click.prevent="editCategory()">update category</b-button>
                            </div>
                        </b-col>
                    </b-row>
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
    components: {
        Widget
    },
    data() {
        return {
            name:"",
            description:"",
            error:""
        }
    },
    methods:{
        editCategory() {
            this.isLoading = true;
            //submit data
            let form = new FormData();
            form.append('title',this.name);
            form.append('description',this.description);

            axios.post(`/api/menu-class/update/${this.menuClass}`,form)
            .then((res) => {
                this.isLoading = false;
                this.$router.push({name:'menuClass.index',params:{domain:this.domain,menu:this.menu,menuClass:res.data.data.menuClass.id}});
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
        //get details
        axios.get(`/api/menu-class/edit/${this.menuClass}`)
        .then((res) => {
            this.isLoading = false;
            this.name = res.data.data.menu.title;
            this.description = res.data.data.description;
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