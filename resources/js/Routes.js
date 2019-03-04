import Vue from 'vue';
import Router from 'vue-router';

import Layout from './components/Layout/layout';
import DomainLayout from './components/Layout/domainLayout';
import MenuLayout from './components/Layout/menuLayout';
import MenuClassLayout from './components/Layout/menuClassLayout';
import ProductLayout from './components/Layout/productLayout';
import Login from './pages/Login/login';
import RegisterUser from './pages/Register/registerUser';
import ErrorPage from './pages/Error/error';
import DashboardPage from './pages/Dashboard/homeDashboard';
import DomainPage from './pages/Dashboard/domainDashboard';
import RegisterDomainPage from './pages/Register/registerDomain';
import DomainIndexPage from './pages/Index/domainIndex';
import ShowDomainPage from './pages/Show/showDomain';
import RegisterMenuPage from './pages/Register/registerMenu';
import MenuIndexPage from './pages/Index/menuIndex';
import RegisterMenuClassPage from './pages/Register/registerMenuClass';
import MenuClassIndexPage from './pages/Index/menuClassIndex';
import RegisterProductPage from './pages/Register/registerProduct';
import ProductIndexPage from './pages/Index/productIndex';
import CartIndexPage from './pages/Index/cartIndex';
import OrderIndexPage from './pages/Index/orderIndex';
import EditDomainPage from './pages/Edit/editDomain';
import EditMenuPage from './pages/Edit/editMenu';
import EditMenuClassPage from './pages/Edit/editMenuClass';
import EditProductPage from './pages/Edit/editProduct';


Vue.use(Router);
export default new Router({
    mode: 'history',
    routes: [
        {
            path: '/login',
            name: 'login',
            component: Login,
        },
        {
            path: '/register',
            name: 'register',
            component: RegisterUser,
        },
        {
            path: '/error',
            name: 'error',
            component: ErrorPage,
        },
        {
            path: '/app',
            name: 'layout',
            component: Layout,
            children: [
                {
                    path: 'home',
                    name: 'home',
                    component: DashboardPage,
                },
                {
                    path:"domain",
                    name:"domain", 
                    component:DomainLayout,
                    children: [
                        {
                            path:"home",
                            name:"domain.home", 
                            component:DomainPage
                        },
                        {
                            path:"register",
                            name:"register.domain", 
                            component:RegisterDomainPage, 
                            meta:{ title: "Create Domain", requiresAuth: true }
                        },
                        {
                            path:"index/:domain", 
                            name:"domain.index", 
                            component:DomainIndexPage, 
                            props:true
                        },
                        {
                            path:"show/:domain", 
                            props:true, 
                            name:"domain.show", 
                            component:ShowDomainPage
                        },
                        {
                            path:"edit/:domain",
                            name:"edit.domain",
                            component:EditDomainPage,
                            props:true
                        }
                    ]
                },
                {
                    path:"menu", 
                    name:"menu", 
                    component:MenuLayout, 
                    children: [
                        {
                            path:"register/:domain", 
                            name:"register.menu",
                            props:true, 
                            component:RegisterMenuPage, 
                            meta:{ title: "Create Menu", reqiuresAuth:true }
                        },
                        {
                            path:"index/:domain/:menu", 
                            name:"menu.index", 
                            component:MenuIndexPage, 
                            props:true,
                        },
                        {
                            path:"edit/:domain/:menu",
                            name:"edit.menu",
                            component:EditMenuPage,
                            props:true
                        }
                    ]
                },
                {
                    path:"menuClass",
                    name:"menuClass",
                    component:MenuClassLayout,
                    children: [
                        {
                            path:"register/:domain/:menu",
                            name:"register.menuClass",
                            component:RegisterMenuClassPage,
                            props:true,
                            meta:{ title: "Create Category", requiresAuth:true }
                        },
                        {
                            path:"index/:domain/:menu/:menuClass",
                            name:"menuClass.index",
                            component:MenuClassIndexPage,
                            props:true
                        },
                        {
                            path:"edit/:domain/:menu/:menuClass",
                            name:"edit.menuClass",
                            component:EditMenuClassPage,
                            props:true
                        }
                    ]
                },
                {
                    path:"product",
                    name:"product",
                    component:ProductLayout,
                    children: [
                        {
                            path:"register/:domain",
                            name:"register.product",
                            component:RegisterProductPage,
                            props:true,
                            meta:{ title: "Create Product", requiresAuth:true }
                        },
                        {
                            path:"index/:product",
                            name:"product.index",
                            component:ProductIndexPage,
                            props:true
                        },
                        {
                            path:"edit/:product",
                            name:"edit.product",
                            component:EditProductPage,
                            props:true
                        }
                    ]
                },
                {
                    path:"cart/index",
                    name:"cart.index",
                    component:CartIndexPage
                },
                {
                    path:"order/index",
                    name:"order.index",
                    component:OrderIndexPage
                }
            ],
        },
    ],
});