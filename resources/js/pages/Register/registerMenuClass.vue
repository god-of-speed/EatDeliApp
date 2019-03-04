<template>
    <div>
        <b-container>
            <h5 class="logo">
                <i class="fa fa-circle text-gray" />
                Add a category
                <i class="fa fa-circle text-warning" />
            </h5>
            <Widget class="mx-auto" customHeader>
                <form class="mt" @submit.prevent="addCategory()">
                    <Alert :message="error" :type="alertType"/>
                    <b-row>
                        <b-col lg="12" md="12" sm="12">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" id="name" placeholder="African Dishes" class="form-control mb-2" v-model="name">
                                <small class="form-text text-muted"></small>
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea type="text" id="description" class="form-control mb-2" v-model="description"></textarea>
                                <small class="form-text text-muted"></small>
                            </div>
                            <div class="d-flex flex-row justify-content-center align-items-center">
                                <b-button size="sm" variant="outline-primary" @click.prevent="addCategory()">add category</b-button>
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
            error:"",
            name: "",
            nameError: "",
            description: "",
            descriptionError: ""
        }
    },
    methods: {
        addCategory() {
            //get form data
            this.isLoading = true;
            var form = new FormData();
            form.append('title',this.name);
            form.append('description',this.description);

            //send data
            axios.post(`/api/menu-class/store/${this.menu}`,form)
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
        }
    },
}
</script>
<style src="./registerDomain.scss" lang="scss"></style>