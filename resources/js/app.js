/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import VueRouter from 'vue-router';
import delayAdapterEnhancer from 'axios-delay';
window.delayAdapterEnhancer =  delayAdapterEnhancer;

import { Form, HasError, AlertError } from 'vform';
window.Form = Form;
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)
Vue.component('pagination', require('laravel-vue-pagination'));

import Tasks from './components/Tasks.vue';
import ImportCSV from './components/ImportCSV.vue';
Vue.component('v-select', VueSelect.VueSelect);

// Vue Event

window.vueEvent = new Vue();

// Create routes

Vue.use(VueRouter)



let routes = [
  { path: '/task_manager/', component: Tasks},
  { path: '/task_manager/tasks', component: Tasks},
  { path: '/task_manager/import-csv', component: ImportCSV},
]

const router = new VueRouter({
  mode: 'history', 
  routes,

})
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    router
});
