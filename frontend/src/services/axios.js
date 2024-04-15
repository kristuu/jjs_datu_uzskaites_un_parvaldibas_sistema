import axios from "axios";
import router from "@/router/router";
import {useFetchDataStore} from "@/stores/fetchDataStore";
const instance = axios.create({
   baseURL: process.env.VUE_APP_LARAVEL_BASE_URL,
   withCredentials: true,
   withXSRFToken: true,
   headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
   }
});

instance.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

instance.interceptors.request.use((config) => {
   const CSRF_TOKEN_MATCH = document.cookie.match(/XSRF-TOKEN=([^;]+)/);
   const token = localStorage.getItem('token');

   if (CSRF_TOKEN_MATCH && CSRF_TOKEN_MATCH[1]) {
      config.headers['X-XSRF-TOKEN'] = decodeURIComponent(CSRF_TOKEN_MATCH[1]);
   }

   if (token) {
      config.headers['Authorization'] = `Bearer ${token}`;
   }

   return config;
});

instance.interceptors.response.use(
    (response) => {
       return response;
    },
    (error) => {
       const { status } = error.response;

       if (status === 401) {
          // Redirect to login page
          router.push({name: `LoginPage`}).then(r => useFetchDataStore().showComponents());
       }

       if (status === 403) {
          // Redirect to home page
          router.push({ name: `HomePage`}).then(r => useFetchDataStore().showComponents());
       }

       return Promise.reject(error);
    }
);

export default instance;