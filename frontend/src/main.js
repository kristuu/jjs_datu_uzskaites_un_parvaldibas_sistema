import { createApp } from 'vue';
import App from './App.vue';
import store from '../src/auth/store.js';
import router from '../src/router/router.js';
// import 'bootstrap/dist/css/bootstrap.css';

const app = createApp(App);

app.use(store);
app.use(router);

app.mount('#app');
