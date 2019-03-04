<template>
    <router-view />
</template>
<script>
export default {
    created() {
        //on refresh
        var storage = JSON.parse(localStorage.getItem('user'));
        if(storage) {
            var api_token = storage.api_token;
            this.$store.dispatch('security/refresh',JSON.parse(localStorage.getItem('user')));
        }else{
            
        }

        const currentPath = this.$router.history.current.path;

        if (currentPath === '/' || currentPath === '/app') {
            this.$router.push('/app/home');
        }

        if(currentPath === '/app/domain') {
            this.$router.push({name:'domain.home'});
        }

        //check localStorage for any cart
        let cart = JSON.parse(localStorage.getItem('cart'));
        if(cart) {
            this.$store.dispatch('cart/initialSetup',cart);
        }

        //intercept every request
        axios.interceptors.request.use((config) => {
            config.headers.Authorization = `Bearer ${this.$store.getters['security/getApiToken']}`;
            return config;
        });
        //intercept every response
        axios.interceptors.response.use((res) => {
            return res;
        },(err) => {
                if(err.response.status == 401) {
                    this.$router.push({name:"login",query:{redirect: to.fullPath }});
                }else if(err.response.status == 404) {
                    this.$router.push({name:"404"});
                }else{
                    return Promise.reject(err);
                }
            });
    },
}

</script>
<style src="./styles/theme.scss" lang="scss" scoped></style>