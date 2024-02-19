import { createStore } from 'vuex';
import router from '../router/router.js';
import axios from "@/services/axios";
// import response from "core-js/internals/is-forced";

export default createStore({
    state: {
        authorized: !!localStorage.getItem('token'),
        formInstance: {},
        isLoading: false,
        errorStatus: null,
        errorMessage: '',
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
        },
        resetFormInstance(state) {
            state.formInstance = {};
        },
        setLoading(state, payload) {
            state.isLoading = payload;
        },
        setErrorStatus(state, payload) {
            state.errorStatus = payload;
        },
        setErrorMessage(state, payload) {
            state.errorMessage = payload;
        },
        clearError(state) {
            state.errorStatus = null;
            state.errorMessage = '';
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
                await router.push({name: 'LoginPage'});
            }
        },
        setFormData({ commit }, payload) {
            commit('SET_FORM_DATA', payload);
        },
        async fetchInstance({commit}, {tableName, id}) {
            console.log(`Fetching data from /${tableName}/${id}`); // step 2
            try {
                const response = await axios.get(`/${tableName}/${id}`);
                console.log('Server response', response); // step 3
                commit('resetFormInstance');
                commit('SET_FORM_DATA', response.data);
                return id;
            }
            catch (error) {
                console.log(error);
                commit('setErrorStatus', error.response.status);
                commit('setErrorMessage', error.response.data.message);
                await router.push({name: 'ErrorView'});
            }
        }
    },
    getters: {
        formInstance: state => state.formInstance,
    },
});