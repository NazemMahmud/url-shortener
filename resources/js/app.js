require('./bootstrap');

import Vue from 'vue';
import VueRouter from "vue-router";
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue';

import router from "./routes/router";
import App from "./layouts/App";

import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-vue/dist/bootstrap-vue.css';

/**
 * Invoke plugins
 */
Vue.use(BootstrapVue); // Make BootstrapVue available throughout the project
Vue.use(IconsPlugin); // Optionally install the BootstrapVue icon components plugin

// Router
Vue.use(VueRouter);

/**
 * create a fresh Vue application instance and attach it to the page.
 */
const app = new Vue({
    el: '#app',
    components: {App},
    router
});
