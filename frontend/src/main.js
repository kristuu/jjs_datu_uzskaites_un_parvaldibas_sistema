import { createApp } from 'vue';
import App from './App.vue';
import store from './auth/store.js';
import router from './router/router.js';
import 'bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap-icons/font/bootstrap-icons.min.css';
import './assets/css/main.css';

import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'

const app = createApp(App);

app.component('VueDatePicker', VueDatePicker);

app.use(store);
app.use(router);

app.mount('#app');