import { createApp } from 'vue';
import App from './App.vue';
import store from './auth/store.js';
import router from './router/router.js';
import axios from '@/services/axios.js';

import PrimeVue from 'primevue/config';
import ToastService from 'primevue/toastservice';
import Toast from "primevue/toast";
import "@/assets/css/theme.css";
import '@/assets/css/main.css';
import "/node_modules/primeflex/primeflex.css";

import IconField from "primevue/iconfield";
import InputIcon from "primevue/inputicon";
import "primeicons/primeicons.css";

import "bootstrap/dist/css/bootstrap.min.css";
import "bootstrap-icons/font/bootstrap-icons.min.css";

import { createI18n } from 'vue-i18n';
import en from '@/locales/en.json';
import lv from '@/locales/lv.json';

const i18n = createI18n({
    legacy: false,
    locale: 'lv',
    fallbackLocale: 'en',
    messages: {
        en: en,
        lv: lv,
    }
})

import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';

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
app.component("Toast", Toast);
app.component("IconField", IconField);
app.component("InputIcon", InputIcon);

app.use(store);
app.use(router);
app.use(PrimeVue);
app.use(ToastService);

axios.get('/get-permissions').then(
    response => {
        window.Laravel.jsPermissions = response.data;
    }
)
import LaravelPermissionToVuejs from "laravel-permission-to-vuejs";

app.use(i18n);
app.use(LaravelPermissionToVuejs);
app.use(VueTelInput, globalOptions);

app.mount('#app');