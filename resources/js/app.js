
require('./bootstrap');

window.Vue = require('vue').default;

Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * create a fresh Vue application instance and attach it to the page.
 */
const app = new Vue({
    el: '#app',
});
