
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import Vue from "vue";
import router from "./Routes";
import store from "./store";
import App from "./app.vue";
import VueTouch from 'vue-touch';
import Trend from 'vuetrend'; 
import BootstrapVue from "bootstrap-vue";
import 'font-awesome/css/font-awesome.css';

Vue.use(BootstrapVue);
Vue.use(VueTouch);
Vue.use(Trend);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

//Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

new Vue({
    el: '#app',
    components: { App },
    router,
    store,
    methods: {
        handleResize() {
            let [clientWidth,clientHeight] = [window.innerWidth,window.innerHeight];
            this.$store.dispatch('layout/setClientProperties',{clientWidth,clientHeight});
        }
    },
    created() {
        window.addEventListener("resize",this.handleResize);
        this.handleResize();
    },
    destroyed() {
        window.removeEventListener('resize',this.handleResize);
    }
});
