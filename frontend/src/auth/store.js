import { createStore } from 'vuex';
import router from '../router/router.js';
import axios from "@/services/axios";

export default createStore({
    state: {
        authorized: !!localStorage.getItem('token')
    },
    // functions that directly mutate the state
    mutations: {
        LOGIN(state) {
            state.authorized = true;
        },
        async LOGOUT(state) {
            try {
                await axios.get("/logout");

                localStorage.removeItem('token');
                state.authorized = false;
            } catch (e) {
                console.error("Radusies kļūda: ", e);
            }
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