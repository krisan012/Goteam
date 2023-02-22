import { createStore } from 'vuex';

export default createStore({
    state: {
        authenticated: localStorage.getItem('api_token') || false,
    },
    mutations: {
        setAuthenticated(state, value) {
            state.authenticated = value;
            localStorage.setItem('api_token', value);
        },
        unsetAuthenticated(state) {
            state.authenticated = false;
            localStorage.removeItem('api_token');
        },
    },
});
