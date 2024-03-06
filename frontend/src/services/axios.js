import axios from 'axios';
const instance = axios.create({
   baseURL: 'http://localhost:8000/api',
   withCredentials: true,
   gPlacesApiUrl: 'https://places.googleapis.com/v1/places',
   gPlacesApiKey: 'AIzaSyCjR1lqBeojrHcLe6hTL4wlxItz78q-XN8',
   headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
   }
});

instance.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

instance.interceptors.request.use((config) => {
   const CSRF_TOKEN = document.cookie.match(/XSRF-TOKEN=([^;]+)/)[1];
   if (CSRF_TOKEN) {
      config.headers['X-XSRF-TOKEN'] = decodeURIComponent(CSRF_TOKEN);
   }
   return config;
});

export default instance;