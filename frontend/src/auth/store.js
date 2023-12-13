import { createStore } from 'vuex';
import router from '../router/router.js';

export default createStore({
    state: {
        authorized: !!localStorage.getItem('token')
    },
    // functions that directly mutate the state
    mutations: {
        LOGIN(state) {
            state.authorized = true;
        },
        LOGOUT(state) {
            state.authorized = false;
        }
    },
    actions: {
        login({ commit }) {
            commit('LOGIN');
        },
        logout({ commit, dispatch }) {
            commit('LOGOUT');
            dispatch('routeToLogin');
        },
        routeToLogin() {
            router.push({ name: 'LoginPage' });
        }
    }
});