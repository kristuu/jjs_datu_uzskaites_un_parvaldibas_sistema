import {createStore} from 'vuex';
import router from '../router/router.js';
import axios from "@/services/axios";

export default createStore({
    state: {
        authorized: !!localStorage.getItem('token'),
        formInstance: {},
    },
    // functions that directly mutate the state
    mutations: {
        LOGIN(state) {
            state.authorized = true;
        },
        // Move async operation from mutation to action
        LOGOUT(state) {
            state.authorized = false;
        },
        SET_FORM_DATA(state, payload) {
            state.formInstance = {...state.formInstance, ...payload};
        }
    },
    actions: {
        login({ commit }) {
            commit('LOGIN');
        },
        // Create async logout operation in action
        async logout({ commit }) {
            try {
                await axios.get("/logout");

                localStorage.removeItem('token');
                commit('LOGOUT');
            } catch (e) {
                console.error("Radusies kļūda: ", e);
            } finally {
                router.push({ name: 'LoginPage' });
            }
        },
        async createInstance (_, { url, data }) {
            try {
                const response = await axios.post(url, data);
                return {
                    status: response.status,
                    title: response.status + ' statuss',
                    message: response.data.data.message,
                };
            } catch (e) {
                console.error(`Error creating resource: `, e);
            }
        },
        setFormData({ commit }, payload) {
            commit('SET_FORM_DATA', payload);
        },
    },
    getters: {
        formInstance: state => state.formInstance,
    },
});