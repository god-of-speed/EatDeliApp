<template>
    <div>
        <div class="d-flex flex-row justify-content-start align-items-center">
            <b-dropdown id="ddown1" class="m-md-2" no-caret>
                <template slot="button-content"><i class="fa fa-modx"></i><span class="sr-only">Menu</span></template>
                <b-dropdown-item :to="{name: 'register.domain'}">Create Domain</b-dropdown-item>
            </b-dropdown>
        </div>
        <b-row id="major">
            <b-col lg="12" sm="12" xs="12">
                <b-card-group deck>
                    <b-card v-for="domain in domains" v-bind:key="domain.id" no-body style="min-width:13rem;margin: 5px 8px 8px 5px;max-width: 13rem;">
                        <b-img class="card-image" thumbnail fluid :src="domain.brandImage" img-alt="Image"/>
                        <div class="card-body">
                            <router-link class="card-link" :to="{name:'domain.index', params:{domain: domain.id}}">
                                <h5 class="card-title"><small>{{ domain.brandname }}</small></h5>
                            </router-link>
                        </div>
                    </b-card>
                </b-card-group>
            </b-col>
        </b-row>
        <div v-if="domains.length > 0" class="mt-3 text-center">
            <b-pagination align="center" :total-rows="totalRows" v-model="currentPage" :per-page="20" />
        </div>
        <div v-if="isLoading" class="loader d-flex flex-row align-items-center justify-content-center">
            <loader/>
        </div>
    </div>
</template>
<script>
import Loader from  "../../components/mixins/loaderMixin.vue";
export default {
    mixins:[Loader],
    data() {
        return {
            domains: [],
            error: null,
            totalRows: null,
            currentPage: null
        }
    },
    created() {
        this.isLoading = true;
        axios.get('/api/domain')
            .then((res) => {
                this.isLoading = false;
                this.error = null;
                if(res.data.data.domains) {
                    this.domains = res.data.data.domains.data;
                    //links
                    this.totalItems = res.data.data.domains.total;
                    this.currentPage = res.data.data.domains.current_page;
                }
            })
            .catch((err) => {
                this.isLoading = false;
                this.error = err.response.data.data.message;
            });
    },
    // beforeRouteUpdate(to,from,next) {
    //     axios.get(to.fullPath)
    //         .then((res) => {
    //             this.error = null;
    //             this.domains = res.data.data.domains.data;
    //             //links
    //             this.totalItems = res.data.data.domains.total;
    //             this.currentPage = res.data.data.domains.current_page;
    //         })
    //         .catch((err) => {
    //             this.error = err.response.data.data.message;
    //         });
    // }
}
</script>
<style src="./dashboard.scss" lang="scss"></style>
<style scoped>
    #ddown1 {
        margin-bottom: 5px;
     }
    #major {
        padding:5px;
    }
    .card-image {
        height:200px !important;
        width:100% !important;
    }
    .card-link:hover {
        color:green;
        text-decoration:none;
    }
</style>