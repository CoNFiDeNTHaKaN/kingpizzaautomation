/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import moment from 'moment'
import Helpers from './helpers'

require('./bootstrap');

window.Vue = require('vue');

Vue.prototype.$date = new Date();
Vue.prototype.$nowTime = function() {
    const dateNow = new Date();
    const minutes = (dateNow.getMinutes().toString() < 10) ? '0' + dateNow.getMinutes().toString() : dateNow.getMinutes().toString();
    return parseInt(dateNow.getHours().toString() + minutes);
}

Vue.prototype.$day = function() {
    // return the day as a monday based 0-indexed integer
    const dateNow = new Date();
    const dayIndex = (dateNow.getDay() === 0) ? 6 : dateNow.getDay() - 1;
    return parseInt(dayIndex);
}

Vue.component('number-two-decimals', {
    props: ["value", "required"],
    template: '<input v-bind:required="requiredAttr" class="currency-field" type="text" v-model="displayValue" @blur="isInputActive = false" @focus="isInputActive = true">',
    data: function() {
        return {
            isInputActive: false,
            requiredAttr: (typeof this.required == 'undefined') ? false : true
        }
    },
    computed: {
        displayValue: {
            get: function() {
                if (this.isInputActive) {
                    // Cursor is inside the input field. unformat display value for user
                    if (typeof this.value == 'undefined') {
                        return '0';
                    };
                    return this.value.toString()
                } else {
                    // User is not modifying now. Format display value for user interface
                    var defaultValue = 0;
                    var number = (typeof this.value != 'undefined') ? this.value.toFixed(2) : defaultValue.toFixed(2);
                    return "£ " + number.replace(/(\d)(?=(\d{3})+(?:\.\d+)?$)/g, "$1,")
                }
            },
            set: function(modifiedValue) {
                // Recalculate value after ignoring "$" and "," in user input
                let newValue = parseFloat(modifiedValue.replace(/[^\d\.]/g, ""))
                // Ensure that it is not NaN
                if (isNaN(newValue)) {
                    newValue = 0
                }
                if (newValue > 9999.99) {
                    newValue = 9999.99;
                }
                // Note: we cannot set this.value as it is a "prop". It needs to be passed to parent component
                // $emit the event so that parent component gets it
                this.$emit('input', newValue)
            }
        }
    }
});

Vue.filter('date', function(value, format = "DD/MM/YYYY HH:mm") {
    if (value && format) {
        return moment(String(value)).format(format)
    }
})

Vue.filter('timeAgo', function(value) {
    if (value) {
        return moment(String(value)).fromNow()
    }
})

Vue.filter('fromNow', function(value) {
    if (value) {
        return moment().diff(moment(String(value)), 'minutes')
    }
})

Vue.filter('replace', function(value, find, replace) {
    return String(value).replaceAll(find, replace);
});

function testScrollDepth() {
    var headerHeight = document.querySelector('.header').offsetHeight;
    var scrollY = window.scrollY;
    if (scrollY > headerHeight) {
        document.querySelector('body').classList.add('scrolled');
    } else {
        document.querySelector('body').classList.remove('scrolled');
    }
}
window.addEventListener('scroll', testScrollDepth);
window.addEventListener('DOMContentLoaded', testScrollDepth)
window.addEventListener('DOMContentLoaded', function() {
    var path = location.pathname;
    if (path.length > 2) {

        var activeLinks = document.querySelectorAll(`[href="${path}"],[href="${location.href}"]`)
        for (var i = 0; i < activeLinks.length; i++) {
            var activeLink = activeLinks[i];
            activeLink.classList.add('selected');
            if (activeLink.parentElement.tagName === "LI") {
                activeLink.parentElement.classList.add('selected');
            }
        }
    }
});
document.querySelector('.mobile-menu-toggle').addEventListener('click', function() {
    document.querySelector('body').classList.toggle('mobile-menu-show');
})
document.querySelector('.filter-toggle').addEventListener('click', function() {
    document.querySelector('body').classList.toggle('filter-show');
})