/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

require('./jquery-plugins/jquery.caret');

Vue.component('file-input', require('./components/FileInput.vue'))
Vue.component('flash', require('./components/Flash.vue'));
Vue.component('thread-view', require('./components/Thread.vue'));
Vue.component(
    'user-notification',
    require('./components/UserNotification.vue')
);

const app = new Vue({
    el: '#app'
});

