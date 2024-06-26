/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('dashboard', require('./components/Dashboard.vue').default);
Vue.component('employees', require('./components/Employees.vue').default);
Vue.component('view-history-logs', require('./components/ViewHistoryLogs.vue').default);
Vue.component('users', require('./components/Users.vue').default);
Vue.component('activity-logs', require('./components/ActivityLogs.vue').default);
Vue.component('rfid-controllers', require('./components/RfidControllers.vue').default);
Vue.component('rfid-doors', require('./components/RfidDoors.vue').default);

// Reports
Vue.component('reports-employee-locations', require('./components/ReportsEmployeeLocations.vue').default);
Vue.component('reports-by-doors', require('./components/ReportsByDoors.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});
