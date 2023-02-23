import { createStore } from 'vuex';
import like from './store/like'

export default createStore({
    modules:{
        like
    },
    state: {
        authenticated: localStorage.getItem('api_token') || false,
        authenticatedUser: JSON.parse(localStorage.getItem('user')) || null,
        authId: localStorage.getItem('authId') || null
    },
    mutations: {
        setAuthenticated(state, value) {
            state.authenticated = value;
            localStorage.setItem('api_token', value);
        },
        setAuthenticatedUser(state, user) {
            state.authenticatedUser = user;
            state.authId = user.id;
            localStorage.setItem('authId', user.id);
            localStorage.setItem('user', JSON.stringify(user));
        },
        unsetAuthenticated(state) {
            state.authenticated = false;
            state.authenticatedUser = null;
            state.authId = null;
            localStorage.removeItem('api_token');
            localStorage.removeItem('authenticatedUser');
            localStorage.removeItem('authId');
        },
    },
});
