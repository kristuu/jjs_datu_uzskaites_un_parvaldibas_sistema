import axios from "@/services/axios.js";
import {defineStore} from "pinia";
import {useDateStore} from "@/stores/dateStore";
import {useErrorStore} from "@/stores/errorStore";
import router from "@/router/router";

export const useFetchDataStore = defineStore({
    id: "fetchData",
    state: () => ({
        instance: {},
        allInstances: [],
        totalInstanceCount: 0,
        isLoading: false,
    }),
    actions: {
        setLoading(payload) {
            this.isLoading = payload;
        },
        setAllInstances(payload) {
            this.allInstances = payload;
        },
        setTotalInstanceCount(payload) {
            this.totalInstanceCount = payload;
        },
        setInstance(payload) {
            this.instance = payload;
        },
        resetAllInstances() {
            this.allInstances = [];
        },
        resetInstance() {
            this.instance = {};
        },
        async fetchDatabaseData(databaseTable) {
            this.resetAllInstances();
            this.setLoading(true);
            try {
                const response = await axios.get(`/${databaseTable}`);
                this.setAllInstances(response.data.instances);
                this.setTotalInstanceCount(response.data.total);
            } catch (error) {
                console.error(`Error fetching ${databaseTable}: `, error);
            } finally {
                this.setLoading(false);
            }
        },
        async fetchInstance(databaseTable, instanceId) {
            this.resetInstance();
            console.log(`Fetching data from /${databaseTable}/${instanceId}`);
            this.setLoading(true);
            try {
                const response = await axios.get(`${databaseTable}/${instanceId}`);
                console.log("Server response: ", response);
                this.setInstance(response.data);
                return instanceId;
            } catch (error) {
                console.log(`Error occurred while fetching exact instance ${instanceId} - `, error);
            } finally {
                this.setLoading(false);
            }
        },
        async createInstance(databaseTable) {
            console.log(this.instance);
            useErrorStore().resetErrorList();
            this.setLoading(true);
            try {
                useDateStore().formatDatesOnInstance(this.instance);
                await axios.post(`/${databaseTable}`, this.instance);
                this.resetInstance();
                await router.push(`/admin/${databaseTable}`);
            } catch (error) {
                useErrorStore().setErrorList(error.response.data);
            } finally {
                this.setLoading(false);
            }
        },
        async updateInstance(databaseTable, instanceId) {
            console.log(this.instance);
            useErrorStore().resetErrorList();
            this.setLoading(true);
            try {
                useDateStore().formatDatesOnInstance(this.instance);
                const response = await axios.put(`/${databaseTable}/${instanceId}`, this.instance);
                console.log(`After update response:` + response);
                this.resetInstance();
                await router.push(`/admin/${databaseTable}`);
            } catch (error) {
                useErrorStore().setErrorList(error.response.data);
            } finally {
                this.setLoading(false);
            }
        },
        async deleteInstance(databaseTable, instanceId) {
            try {
                await axios.delete(`/${databaseTable}/${instanceId}`);
                await this.fetchDatabaseData(databaseTable);
            } catch (error) {
                console.error(`Error deleting ${databaseTable}: ${instanceId} - `, error);
            }
        }
    }
});