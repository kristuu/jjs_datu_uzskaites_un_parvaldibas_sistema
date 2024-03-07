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
        instances: null,
        totalInstances: 0
    },
    // functions that directly mutate the state
    mutations: {
        SET_FORM_DATA(state, payload) {
            state.formInstance = {...state.formInstance, ...payload};
        },
        resetInstances(state) {
            state.instances = {};
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
        },
        SET_INSTANCES(state, payload) {
            state.instances = payload;
        },
        SET_TOTAL_INSTANCES(state, payload) {
            state.totalInstances = payload;
        }
    },
    actions: {
        // Create async logout operation in action
        async logout({ commit }) {
            try {
                await axios.get("/logout");
            } catch (e) {
                console.error("Radusies kļūda: ", e);
            } finally {
                await router.push({name: 'LoginPage'});
            }
        },
        setFormData({ commit }, payload) {
            commit('SET_FORM_DATA', payload);
        },
        async fetchInstance({commit}, {databaseTable, instanceId}) {
            console.log(`Fetching data from /${databaseTable}/${instanceId}`); // step 2
            commit('setLoading', true);
            try {
                commit('resetFormInstance');
                const response = await axios.get(`/${databaseTable}/${instanceId}`);
                console.log('Server response', response); // step 3
                commit('SET_FORM_DATA', response.data);
                return instanceId;
            }
            catch (error) {
                console.log(error);
                commit('setErrorStatus', error.response.status);
                commit('setErrorMessage', error.response.data.message);
                await router.push({name: 'ErrorView'});
            }
            finally {
                commit('setLoading', false);
            }
        },
        async fetchDatabaseData({commit}, databaseTable) {
            commit('resetInstances');
            commit('setLoading', true);
            try {
                const response = await axios.get(`/${databaseTable}`);
                let instances = response.data.instances;
                commit('SET_INSTANCES', instances);
                commit('SET_TOTAL_INSTANCES', response.data.total);
            } catch (error) {
                console.error(`Error fetching ${databaseTable}: `, error);
            } finally {
                commit('setLoading', false);
            }
        },
        async deleteInstance({dispatch}, {databaseTable, instanceId}) {
            try {
                await axios.delete(`/${databaseTable}/${instanceId}`);
                dispatch('fetchDatabaseData', databaseTable);
            } catch (error) {
                console.error(`Error fetching ${databaseTable}: `, error);
            }
        }
    },
    getters: {
        formInstance: state => state.formInstance,
        instances: state => state.instances,
    },
});