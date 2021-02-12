/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
window.Vue = require('vue');

// Define components
Vue.component('pagination', require('laravel-vue-pagination'));
Vue.component('default-message',require('./components/DefaultMessage.vue').default);
Vue.component('doughnut-chart',require('./components/Doughnut.vue').default);
Vue.component('bar-chart',require('./components/BarChart.vue').default);


// Form
import { Form, HasError, AlertError } from 'vform';
window.Form = Form;
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)

// Axios Delay
import delayAdapterEnhancer from 'axios-delay';
window.delayAdapterEnhancer =  delayAdapterEnhancer;

// Vue Excel
import Vue from 'vue'
import excel from 'vue-excel-export'
Vue.use(excel)

// Vue Select 
import vSelect from 'vue-select'
Vue.component('v-select', vSelect)

// Vue Leaflet
import 'leaflet/dist/leaflet.css';
import { Icon } from "leaflet";
delete Icon.Default.prototype._getIconUrl;
Icon.Default.mergeOptions({
  iconRetinaUrl: '/images/vendor/leaflet/dist/marker-icon-2x.png',
  iconUrl: '/images/vendor/leaflet/dist/marker-icon.png',
  shadowUrl: '/images/vendor/leaflet/dist/marker-shadow.png',
});

// Vue Notification
import Notifications from 'vue-notification'
Vue.use(Notifications)

// Vue Event
window.vueEvent = new Vue();

// Import components
import Tasks from './components/Tasks.vue';
import ImportCSV from './components/ImportCSV.vue';
import ClientsMap from './components/ClientsMap.vue';
import Reports from './components/Reports.vue';
import ProjectInfo from './components/ProjectInfo.vue';

// Create routes
import VueRouter from 'vue-router';
Vue.use(VueRouter)

let routes = [
  { path: '/', component: Tasks},
  { path: '/tasks', component: Tasks},
  { path: '/import-csv', component: ImportCSV},
  { path: '/clients-map', component: ClientsMap},
  { path: '/reports', component: Reports},
  { path: '/project-info', component: ProjectInfo},
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
    router,
    data: {
      checkImportCSV: checkImportCSV
    },
});
window.app = app;