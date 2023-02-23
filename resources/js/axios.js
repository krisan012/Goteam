import axios from 'axios';
import router from './router';
import store from './store';

window.axios = axios;

const instance = axios.create();

instance.interceptors.request.use(
    (config) => {
        const token = localStorage.getItem('token');
        if (token) {
            config.headers.Authorization = `Bearer ${token}`;
        }

        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);

instance.interceptors.response.use(
    (response) => {
        return response;
    },
    (error) => {
        if (error.response.status === 401 || error.response.status === 419) {
            store.commit('unsetAuthenticated');
            router.push({ name: 'Login' });
        }

        return Promise.reject(error);
    }
);

instance.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

export default instance;
