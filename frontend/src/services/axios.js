import axios from 'axios';
const instance = axios.create({
   baseURL: 'http://localhost:8000/api',
   withCredentials: true,
   gPlacesApiUrl: 'https://places.googleapis.com/v1/places',
   gPlacesApiKey: 'AIzaSyCjR1lqBeojrHcLe6hTL4wlxItz78q-XN8'
});

instance.interceptors.request.use((config) => {
   config.headers.Authorization = `Bearer ${localStorage.getItem('token')}`
   return config;
});

export default instance;