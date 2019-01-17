
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
import Vue from 'vue'
import axios from 'axios'
import VueRouter from 'vue-router'
import BootstrapVue from 'bootstrap-vue'
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
window.Vue = Vue;
window.axios = axios;

Vue.use(VueRouter);
Vue.use(BootstrapVue);
Vue.config.productionTip = false
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


let NavTop = require('./components/partials/NavTop.vue');

let HomePage = require('./components/pages/homepage.vue');
let addProduct = require('./components/pages/addproduct.vue');
let editProduct = require('./components/pages/editproduct.vue');

const routes = [
    {path: '/', name: 'homepage', component: HomePage},
    {path: '/addProduct', name: 'addProduct', component: addProduct},
    {path: '/editProduct/:id', name: 'editProduct', component: editProduct},
]
const router = new VueRouter({
    mode:'history',
    routes,
})
const app = new Vue({
    el: '#app',
    router,
    components:{
        NavTop
    }
});
