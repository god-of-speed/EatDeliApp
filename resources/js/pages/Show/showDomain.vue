<template>
    <b-container>
        <Widget class="mx-auto" customHeader>
            <Alert :message="error" :type="alertType"/>
            <b-row>
                <b-col lg="4" md="4" sm="12">
                    <div id="brand-image-holder" class="d-flex flex-row justify-content-center align-items-center">
                        <b-img v-bind="mainProps" ref="brandImageHolder" :src="domainImage" rounded="top" alt="Top-rounded image" />
                    </div>           
                </b-col> 
                <b-col id="info" lg="4" md="4" sm="12">
                    <h6 id="brandname">{{ domainName }}</h6>
                    <div>
                        <strong>description:</strong><br>
                        <span>{{ description }}</span><br>
                        <strong>associated with:</strong><br><br>
                        <b-row>
                            <b-link class="filter-link" v-for="tag in tags" v-bind:key="tag.id">{{ tag.tag }}</b-link>
                        </b-row>
                        <br>
                        <hr>
                        <b-button @click="addMenu(domain)" size="sm" variant="outline-primary" v-if="isAdmin && !showMenu">add menu</b-button>
                        <b-button v-if="isAdmin && showMenu" @click.prevent="removeMenu()" size="sm" variant="outline-danger">remove menu</b-button>
                        <b-button size="sm" variant="outline-primary" :to="{name:'register.product',params:{domain:domain}}" v-if="isAdmin">add product</b-button>
                        <b-button size="sm" variant="outline-primary" :to="{name:'edit.domain',params:{domain:domain}}" v-if="isAdmin">edit</b-button>
                    </div>
                </b-col>
                <b-col v-if="showMenu" lg="4" md="4">
                    <h5 class="logo">
                        <i class="fa fa-circle text-gray" />
                        Add a menu to this domain
                        <i class="fa fa-circle text-warning" />
                    </h5>
                    <form id="addMenu" class="mt" @submit.prevent="addMenuAction()">
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
                                    <b-button size="sm" variant="outline-primary" @click.prevent="addMenuAction()">add menu</b-button>
                                </div>
                            </b-col>
                        </b-row>
                    </form>
                </b-col>
            </b-row>
        </Widget>
        <div v-if="isLoading" class="loader d-flex flex-row align-items-center justify-content-center">
            <loader/>
        </div>
    </b-container>
</template>
<script>
    import Widget from '../../components/Widget/Widget';
    import Loader from  "../../components/mixins/loaderMixin.vue";
    import Alert from  "../../components/mixins/alertMixin.vue";
    
    export default {
        mixins:[Loader,Alert],
        props: {
            domain:{
                required:true
            }
        },
        components: {
            Widget
        },
        data() {
            return {
                mainProps: { width: 250, height: 250, class: 'm1' },
                showMenu:false,
                menuname:"",
                menuDescription:"",
                error:null,
                domainName: "",
                domainImage: "",
                isAdmin: false,
                description:"",
                tags:null
            }
        },
        computed: {
            hasError() {
                return this.error;
            }
        },
        methods: {
            removeMenu() {
                this.menuname = "";
                this.menuDescription = "";
                return this.showMenu = false;
            },
            addMenu(domain) {
                let [clientWidth,clientHeight] = this.$store.getters['layout/getClientProperties'];
                if(clientWidth > 760 && clientHeight > 760) {
                    return this.showMenu = true;
                }else{
                    return this.$router.push({name:'register.menu', params:{domain:this.domain}});
                }
            },
            addMenuAction() {
                //get form data
                this.isLoading = true;
                var form = new FormData();
                form.append('title',this.menuname);
                form.append('description',this.menuDescription);

                //send data
                axios.post(`/api/menu/store/${this.domain}`,form)
                .then((res) => {
                    this.isLoading = false;
                    this.error = "";
                    this.$router.push({name:'menu.index', params: {domain:this.domain,menu: res.data.data.menu.id}});
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
            axios.get(`/api/domain/show/${this.domain}`)
            .then((res) => {
                this.isLoading = false;
                this.error = "";
                this.domainName = res.data.data.domain.brandname;
                this.domainImage = res.data.data.domain.brandImage;
                this.description = res.data.data.domain.description;
                this.tags = res.data.data.tags;
                this.isAdmin = res.data.data.isAdmin;
            })
            .catch((err) => {
                this.isLoading = false;
                this.alertType = "danger";
                this.error = err.response.data;
            });
        }
    }
</script>
<style src="./show.scss" lang="scss" scoped></style>