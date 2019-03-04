export default {
    namespaced:true,
    state: {
        loading: false,
        error: "",
        sidebar:'active',
        overlay:'overlay'
    },
    getters: {
        isLoading(state) {
            return state.loading;
        },
        hasError(state) {
            return state.error;
        },
        getSidebar(state) {
            return state.sidebar;
        },
        getOverlay(state) {
            return state.overlay;
        }
    },
    mutations: {
        ['close'](state) {
            state.sidebar = "active";
            state.overlay = "overlay";
        },
        ['open'](state) {
            state.sidebar = "";
            state.overlay = "overlay active";
        },
        ['Error'](state,payload) {
            state.error = payload;
        },
        ['Load'](state,payload) {
            state.loading = true;
        },
        ['Clear_Loading'](state) {
            state.loading = false;
        },
        ['Clear_Error'](state) {
            state.error = "";
        }
    },
    actions: {
        close({commit}) {
            commit('close');
        },
        open({commit}) {
            commit('open');
        },
        loadError({commit},payload) {
            commit('Error',payload);
        },
        clearError({commit}) {
            commit('Clear_Error');
        },
        clearLoading({commit}) {
            commit('Clear_Loading');
        },
        load({commit}) {
            commit('Load');
        }
    }
}