<template>
    <div>
        <b-container>
            <h5 class="logo">
                <i class="fa fa-circle text-gray" />
                Update menu
                <i class="fa fa-circle text-warning" />
            </h5>
            <Widget class="mx-auto" customHeader>
                <form class="mt" @submit.prevent="editMenu()">
                    <Alert :message="error" :type="alertType"/>
                    <b-row>
                        <b-col lg="12" md="12" sm="12">
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
                                <b-button size="sm" variant="outline-primary" @click.prevent="editMenu()">update menu</b-button>
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
        domain:{
            required:true
        },
        menu:{
            required:true
        }
    },
    components: {
        Widget
    },
    data() {
        return {
            menuname:"",
            menuDescription:"",
            error:""
        }
    },
    methods:{
        editMenu() {
            this.isLoading = true;
            //update menu
            let form =  new FormData();
            form.append('title',this.menuname);
            form.append('description',this.menuDescription);

            axios.post(`/api/menu/update/${this.menu}`,form)
            .then((res) => {
                this.isLoading = false;
                //redirect
                this.$router.push({name:'menu.index',params:{domain:this.domain,menu:res.data.data.menu.id}});
            })
            .catch((err) => {
                this.isLoading = false;
                this.alertType = "danger";
                this.error = err.response.data;
            });
        }
    },
    created() {
        //get menu details
        this.isLoading = true;
        axios.get(`/api/menu/edit/${this.menu}`)
        .then((res) => {
            this.isLoading = false;
            this.menuname = res.data.data.menu.title;
            this.menuDescription = res.data.data.menu.description;
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