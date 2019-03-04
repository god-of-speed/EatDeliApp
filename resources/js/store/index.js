import Vue from "vue";
import Vuex from "vuex";
import SecurityModule from "./security";
import InitialModule from './initial/index';
import CartModule from './initial/cart';
import LayoutModule from './layout';

Vue.use(Vuex);

export default new Vuex.Store({
    modules:{
        layout:LayoutModule,
        security:SecurityModule,
        initial:InitialModule,
        cart: CartModule
    }
});