
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example-component', require('./components/ExampleComponent.vue'));

/*
const app = new Vue({
    el: '#app'
});
*/

var mouseDown = false;

$(document).on('mousedown mouseup', function(event) {
    if(event.type == 'mousedown') {
        mouseDown = true;
    } else {
        mouseDown = false;
    }
});

$('td.check').on('click', function(event) {
    $(this).find('input').prop('checked', !$(this).find('input').prop('checked')).change();
}).find('input[type="checkbox"]').on('change', function() {
    var parent = $(this).parent('td');
    var value = $(this).prop('checked');

    if(value) {
        parent.addClass('checked');
    } else {
        parent.removeClass('checked');
    }
});