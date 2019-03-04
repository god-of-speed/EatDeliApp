<template>
  <div class="login-page">
    <b-container>
      <h5 class="logo">
        <i class="fa fa-circle text-gray" />
        Login
        <i class="fa fa-circle text-warning" />
      </h5>
      <Widget class="mx-auto" customHeader>
        <form class="mt" @submit.prevent="login()">
          <Alert :message="error" :type="alertType"/>
          <div class="form-group">
            <input class="form-control no-border" v-model="username"
              required type="text" name="username" placeholder="Username" />
          </div>
          <div class="form-group">
            <input class="form-control no-border" v-model="password"
            required type="password" name="password" placeholder="Password" />
          </div>
          <div class="clearfix">
            <div class="btn-toolbar float-right">
              <b-button :to="{name:'register'}" size="sm" variant="default">Create an Account</b-button>
              <b-button @click.prevent="login()" type="submit" size="sm" variant="primary">Login</b-button>
            </div>
          </div>
          <div class="row no-gutters mt-3">
            <!-- <div class="col">
              <div class="abc-checkbox">
                <input
                  type="checkbox"
                  id="checkbox"
                />
                <label for="checkbox" class="text-muted fs-sm">Keep me signed in</label>
              </div>
            </div> -->
            <div class="col">
              <a class="mt-sm" href="">Trouble with account?</a>
            </div>
          </div>
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
  name: 'LoginPage',
  mixins:[Loader,Alert],
  components: { Widget },
  data() {
    return {
      error: null,
      username:"",
      password:""
    };
  },
  methods: {
    login() {
      var form = new FormData();
      form.append('username',this.username);
      form.append('password',this.password);
      //send data
      this.isLoading = true;
      axios.post('/api/login',form)
      .then((res) => {
          this.isLoading = false;
          this.error = null;
          this.$store.dispatch('security/authenticationSuccess',res.data.data);
          localStorage.setItem('user',JSON.stringify(res.data.data));
          //get redirect from route parameter
          var redirect = this.$route.query.redirect;
          if(redirect != null) {
              this.$router.push(redirect);
          }else{
              this.$router.push({name:"home"});
          }
      })
      .catch((err) => {
          this.isLoading = false;
          let errors = null;
          if(err.response.data.errors) {
              errors = [];
              for(let username in err.response.data.errors.username) {
                  errors.push(err.response.data.errors.username[username]);
              } 
              for(let password in err.response.data.errors.password) {
                  errors.push(err.response.data.errors.password[password]);
              }
          }else{
            errors = err.response.data.message;
          }
          this.alertType = "danger";
          this.error = errors;
      });
    }
  },
  created() {
    let data = JSON.parse(localStorage.getItem('user'));
    //set redirect
    let redirect = this.$route.query.redirect;
    if(data) {
      if ( data.api_token) {
      if(redirect) {
        this.$router.push({name: redirect});
      }
      this.$router.push({name: 'home'});
    }
    }
  },
};
</script>

<style src="./login.scss" lang="scss" scoped />
