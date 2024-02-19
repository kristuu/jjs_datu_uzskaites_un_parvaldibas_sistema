import { createApp } from 'vue';
import App from './App.vue';
import store from './auth/store.js';
import router from './router/router.js';
import axios from '@/services/axios.js';

import 'bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap-icons/font/bootstrap-icons.min.css';

import './assets/css/main.css';

import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'

import VueTelInput from 'vue-tel-input';
import 'vue-tel-input/vue-tel-input.css';

const globalOptions = {
    mode: 'international',
    validCharactersOnly: true,
    preferredCountries: [
        'LV', 'LT', 'EE',
    ],
    defaultCountry: 'LV',
    disabledFetchingCountry: true,
    dropdownOptions: {
        showSearchBox: true,
    },
    inputOptions: {
        showDialCode: true,
        placeholder: null,
    }
};

const app = createApp(App);

app.component('VueDatePicker', VueDatePicker);

app.use(store);
app.use(router);

axios.get('/get-permissions').then(
    response => {
        window.Laravel.jsPermissions = response.data;
    }
)
import LaravelPermissionToVuejs from "laravel-permission-to-vuejs";

app.use(LaravelPermissionToVuejs);

app.use(VueTelInput, globalOptions);

app.mount('#app');