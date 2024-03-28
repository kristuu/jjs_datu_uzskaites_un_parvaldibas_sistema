import axios from "axios";
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

export default instance;