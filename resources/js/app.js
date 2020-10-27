require('./bootstrap');

window.Vue = require('vue');

Vue.component('items', require('./components/Items.vue').default);

const app = new Vue({
    el: '#app'
});