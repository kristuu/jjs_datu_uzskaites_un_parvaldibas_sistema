import { createApp } from 'vue';
import App from './App.vue';
import { createPinia } from 'pinia';

import router from './router/router.js';
import axios from '@/services/axios.js';

import PrimeVue from 'primevue/config';
import ToastService from 'primevue/toastservice';
import Toast from "primevue/toast";

import "bootstrap/dist/css/bootstrap-utilities.min.css";
import "bootstrap/dist/css/bootstrap-grid.min.css";
import "bootstrap-icons/font/bootstrap-icons.min.css";

import "@/assets/css/theme.css";
import '@/assets/css/main.css';
import "@/assets/fonts/open_sans/stylesheet.css";
import "@/assets/fonts/ubuntu/stylesheet.css";
import "/node_modules/primeflex/primeflex.css";

const pinia = createPinia();
const app = createApp(App);

import IconField from "primevue/iconfield";
import InputIcon from "primevue/inputicon";
import "primeicons/primeicons.css";

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

app.component('VueDatePicker', VueDatePicker);
app.component("Toast", Toast);
app.component("IconField", IconField);
app.component("InputIcon", InputIcon);

app.use(pinia);
app.use(router);
app.use(PrimeVue);
app.use(ToastService);

app.directive('ripple', Ripple);
app.directive('styleclass', StyleClass);

app.config.errorHandler = function (err, vm, info) {
    console.error('Error detected:', err);
    console.error('Component that caused error:', vm);
    console.error('Additional webpack-specific information:', info);
};

import LaravelPermissionToVuejs from "laravel-permission-to-vuejs";
import Ripple from "primevue/ripple";
import StyleClass from "primevue/styleclass";

app.use(i18n);
app.use(LaravelPermissionToVuejs);
app.use(VueTelInput, globalOptions);

app.mount('#app');