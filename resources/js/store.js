import { createStore } from 'vuex';
import like from './store/like'

export default createStore({
    modules:{
        like
    },
    state: {
        authenticated: localStorage.getItem('api_token') || false,
    },
    mutations: {
        setAuthenticated(state, value) {
            state.authenticated = value;
            localStorage.setItem('api_token', value);
        },
        unsetAuthenticated(state) {
            console.log('unsetting the state')
            state.authenticated = false;
            localStorage.removeItem('api_token');
        },
    },
});
