export default {
    namespaced:true,
    state: {
        loading:false,
        authenticated:false,
        user:null,
        error:null,
        api_token:""
    },
    getters: {
        isLoading(state) {
            return state.loading;
        },
        hasError(state) {
            return state.error;
        },
        isAuthenticated(state) {
            return state.authenticated;
        },
        getUser(state) {
            return state.user;
        },
        getApiToken(state) {
            return state.api_token;
        } 
    },
    mutations: {
        ['Authentication_Success'](state,payload) {
            state.loading = false;
            state.authenticated = true;
            state.user = { 
                firstname: payload.firstname,
                lastname: payload.lastname,
                username: payload.username 
            };
            state.error = "";
            state.api_token = payload.api_token;
        },
        ['On_Refresh'](state,payload) {
            state.loading = false;
            state.authenticated = true;
            state.user = { 
                firstname: payload.firstname,
                lastname: payload.lastname,
                username: payload.username 
            };
            state.error = "";
            state.api_token = payload.api_token;
        }
    },
    actions: {
        authenticationSuccess({commit},payload) {
            commit('Authentication_Success',payload);
        },
        refresh({commit},payload) {
            commit('On_Refresh',payload);
        }
    }
}