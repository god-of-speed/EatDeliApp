export default  {
    namespaced:true,
    state: {
        cart: []
    },
    getters: {
        getCart(state) {
            return state.cart;
        }
    },
    mutations: {
        ['ADD_TO_CART'](state,payload) {
            var alreadyExist = false;
            var pos = 0;
            for(var i=0; i<state.cart.length; i++) {
                if(state.cart[i].product.id == payload.id) {
                    alreadyExist = true;
                    pos = i;
                }
            }
            if(alreadyExist) {
                state.cart[pos].quantity += 1;
            }else{
                let lastIndex = state.cart.length === 0 ? 0 : state.cart[state.cart.length - 1].index;
                let product = {
                    index:lastIndex + 1,
                    product:payload,
                    quantity:1
                };
                state.cart.push(product);
            }
            localStorage.setItem('cart',JSON.stringify(state.cart));
        },
        ['REMOVE_FROM_CART'](state,payload) {
            var alreadyExist = false;
            var pos = 0;
            for(var i=0; i<state.cart.length; i++) {
                if(state.cart[i].product.id == payload.id) {
                    alreadyExist = true;
                    pos = i;
                }
            }
            if(alreadyExist) {
                if(state.cart[pos].quantity > 1) {
                    state.cart[pos].quantity -= 1;
                }else {
                    state.cart.splice(pos,1);
                }
            }

            localStorage.setItem('cart',JSON.stringify(state.cart));
        },
        ['ORDER_FROM_CART'](state,payload) {
            var alreadyExist = false;
            var pos = 0;
            for(var i=0; i<state.cart.length; i++) {
                if(state.cart[i].product.id == payload.id) {
                    alreadyExist = true;
                    pos = i;
                }
            }
            if(alreadyExist) {
                state.cart.splice(pos,1);
            }

            localStorage.setItem('cart',JSON.stringify(state.cart));
        },
        ['INITIAL_SETUP'](state,payload) {
            state.cart = payload;
        },
        ['REMOVE_ALL'](state) {
            state.cart = [];
            localStorage.removeItem('cart');
        }
    },
    actions: {
        addToCart({commit},payload) {
            commit('ADD_TO_CART',payload);
        },
        removeFromCart({commit},payload) {
            commit('REMOVE_FROM_CART',payload);
        },
        initialSetup({commit},payload) {
            commit('INITIAL_SETUP',payload);
        },
        removeAll({commit}) {
            commit('REMOVE_ALL');
        },
        orderFromCart({commit},payload) {
            commit('ORDER_FROM_CART',payload);
        }
    }
}