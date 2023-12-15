import { createApp } from 'vue';
import App from './App.vue';
import store from './auth/store.js';
import router from './router/router.js';
import './assets/css/main.css'

const app = createApp(App);

app.use(store);
app.use(router);

app.mount('#app');