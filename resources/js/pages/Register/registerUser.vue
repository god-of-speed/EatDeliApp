<template>
    <div class="reg-page">
        <b-container>
            <h5 class="logo">
                <i class="fa fa-circle text-gray" />
                Register
                <i class="fa fa-circle text-warning" />
            </h5>
            <Widget class="mx-auto" customHeader>
                <form class="mt" @submit.prevent="register()">
                    <Alert :message="error" :type="alertType"/>
                    <b-row>
                        <b-col lg="12" md="12" sm="12">
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" id="username" placeholder="frankOT" class="form-control mb-2" v-model="username">
                                <small class="form-text text-muted"></small>
                            </div>
                            <div class="form-group">
                                <label for="firstname">Firstname</label>
                                <input type="text" id="firstname" placeholder="frank" class="form-control mb-2" v-model="firstname">
                                <small class="form-text text-muted"></small>
                            </div>
                            <div class="form-group">
                                <label for="lastname">lastname</label>
                                <input type="text" id="lastname" placeholder="lin" class="form-control mb-2" v-model="lastname">
                                <small class="form-text text-muted"></small>
                            </div>
                            <div class="form-group">
                                <label for="password">password</label>
                                <input type="password" id="password" placeholder="frank" class="form-control mb-2" v-model="password">
                                <small class="form-text text-muted"></small>
                            </div>
                            <div class="clearfix">
                                <div class="btn-toolbar float-right">
                                    <b-button :to="{name:'login'}" size="sm" variant="default"> login </b-button>
                                    <b-button @click.prevent="register()" type="submit" size="sm" variant="primary">sign up</b-button>
                                </div>
                            </div>
                            <div class="row no-gutters mt-3">
                                <div class="col">
                                    <a class="mt-sm" href="">Trouble with account?</a>
                                </div>
                            </div>
                        </b-col>
                    </b-row>
                </form>
                <div v-if="isLoading" class="loader d-flex flex-row align-items-center justify-content-center">
                    <loader/>
                </div>
            </Widget>
        </b-container>
        <footer class="footer">
            2019 &copy; EatDeli INC. <br>Sing. Admin Dashboard Template.
        </footer>
    </div>
</template>
<script>
import Widget from '../../components/Widget/Widget';
import Loader from  "../../components/mixins/loaderMixin.vue";
import Alert from  "../../components/mixins/alertMixin.vue";
export default {
    mixins:[Loader,Alert],
    components: {
        Widget
    },
    data() {
        return {
            username:"",
            firstname:"",
            lastname:"",
            password:"",
            error:""
        }
    },
    methods:{
        register() {
            //submit data
            this.isLoading = true;
            let form = new FormData();
            form.append('username',this.username);
            form.append('firstname',this.firstname);
            form.append('lastname',this.lastname);
            form.append('password',this.password);

            axios.post('/api/register',form)
            .then((res) => {
                this.isLoading = false;
                this.error = null;
                localStorage.setItem('user',JSON.stringify(res.data.data));
                this.$store.dispatch('security/authenticationSuccess',res.data.data);
                this.$router.push({name:"home"});
            })
            .catch((err) => {
                this.isLoading = false;
                let errors = null;
                if(err.response.data.errors) {
                    errors = [];
                    for(let element in err.response.data.errors.username) {
                        errors.push(err.response.data.errors.username[element]);
                    }
                    for(let element in err.response.data.errors.firstname) {
                        errors.push(err.response.data.errors.firstname[element]);
                    }
                    for(let element in err.response.data.errors.lastname) {
                        errors.push(err.response.data.errors.lastname[element]);
                    }
                    for(let element in err.response.data.errors.password) {
                        errors.push(err.response.data.errors.password[element]);
                    }
                }else{
                    errors = err.response.data.message;
                }
                this.alertType = "danger";
                this.error = errors;
            });
        }
    }
}
</script>
<style lang="scss">
@import '../../styles/app';

.reg-page {
  padding-top: 20vh;
  height: 100%;

  .widget {
    max-width: 460px;
    padding: 30px !important;

    h1,
    h2,
    h3,
    h4,
    h5,
    h6 {
      font-weight: $font-weight-normal;
    }
  }

  .footer {
    margin-bottom: 25px;
    font-size: 13px;
    color: #636c72;
    text-align: center;

    @media (min-height: 600px) {
      position: fixed;
      bottom: 0;
      left: 0;
      right: 0;
    }
  }

  .logo {
    margin-top: 15px;
    margin-bottom: 15px;
    text-align: center;
    font-weight: $font-weight-normal;

    i {
      font-size: 13px;
      margin: 0 20px;
    }
  }
}

</style>