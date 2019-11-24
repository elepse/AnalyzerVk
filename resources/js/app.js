require('./bootstrap');


import Vue from 'vue'
import Vuetify from 'vuetify'
import {store} from './store'
import axios from 'axios'
import VueAuth from '@websanova/vue-auth'
import 'es6-promise/auto'
import VueAxios from 'vue-axios'
import auth from './auth'
import router from './router'
import VueRouter from 'vue-router'
import Index from './components/Index'

// Set Vue globally
window.Vue = Vue;


Vue.use(Vuetify);

//set Vue router
Vue.router = router;
Vue.use(VueRouter);

//set Vue Auth
Vue.use(VueAxios, axios);
axios.defaults.baseURL = 'http://127.0.0.1:8000' + '/api';
Vue.use(VueAuth, auth);


Vue.component('index', Index);

const app = new Vue({
    el: '#app',
    vuetify: new Vuetify(),
    router,
    store
});
