import axios from "@/services/axios.js";
import { defineStore } from "pinia";
import { useDateStore } from "@/stores/dateStore";
import { useErrorStore } from "@/stores/errorStore";
import router from "@/router/router";

export const useFetchDataStore = defineStore({
  id: "fetchData",
  state: () => ({
    instance: {},
    allInstances: [],
    totalInstanceCount: 0,
    isFetching: false,
    isProcessing: false,
    show: false,
    globalFilterFields: [],
  }),
  actions: {
    setIsFetching(payload) {
      this.isFetching = payload;
    },
    setIsProcessing(payload) {
      this.isProcessing = payload;
    },
    toggleShowComponentsState() {
      this.show = !this.show;
    },
    setProperty(propertyName, value) {
      this.instance[propertyName] = value;
    },
    setAllInstances(payload) {
      this.allInstances = payload;
    },
    setTotalInstanceCount(payload) {
      this.totalInstanceCount = payload;
    },
    setGlobalFilterFields(payload) {
      this.globalFilterFields = payload;
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
    resetGlobalFilterFields() {
      this.globalFilterFields = [];
    },
    async fetchDatabaseData(databaseTable) {
      this.resetAllInstances();
      this.resetGlobalFilterFields();
      this.setIsFetching(true);
      try {
        const response = await axios.get(`/api/${databaseTable}`);
        this.setAllInstances(response.data.instances);
        this.setTotalInstanceCount(response.data.total);
        if (response.data.globalFilterFields !== []) {
          this.setGlobalFilterFields(response.data.globalFilterFields);
        }
      } catch (error) {
        console.error(`Error fetching ${databaseTable}: `, error);
      } finally {
        this.setIsFetching(false);
      }
    },
    async fetchInstance(databaseTable, instanceId) {
      this.resetInstance();
      console.log(`Fetching data from /${databaseTable}/${instanceId}`);
      this.setIsFetching(true);
      try {
        const response = await axios.get(`/api/${databaseTable}/${instanceId}`);
        console.log("Server response: ", response);
        this.setInstance(response.data);
        return instanceId;
      } catch (error) {
        console.log(
          `Error occurred while fetching exact instance ${instanceId} - `,
          error
        );
      } finally {
        this.setIsFetching(false);
      }
    },
    async createInstance(databaseTable) {
      console.log(this.instance);
      useErrorStore().resetErrorList();
      this.setIsProcessing(true);
      try {
        useDateStore().formatDatesOnInstance(this.instance);
        await axios.get(`/sanctum/csrf-cookie`);
        await axios.post(`/api/${databaseTable}`, this.instance);
        this.resetInstance();
        await router.push(`/admin/${databaseTable}`);
      } catch (error) {
        useErrorStore().setErrorList(error.response.data);
      } finally {
        this.setIsProcessing(false);
      }
    },
    async updateInstance(databaseTable, instanceId) {
      console.log(this.instance);
      useErrorStore().resetErrorList();
      this.setIsProcessing(true);
      try {
        useDateStore().formatDatesOnInstance(this.instance);
        await axios.get(`/sanctum/csrf-cookie`);
        const response = await axios.put(
          `/api/${databaseTable}/${instanceId}`,
          this.instance
        );
        console.log(`Server response when updating: ` + response);
        this.resetInstance();
        await router.push(`/admin/${databaseTable}`);
      } catch (error) {
        console.log(error);
        useErrorStore().setErrorList(error.response.data);
      } finally {
        this.setIsProcessing(false);
      }
    },
    async deleteInstance(databaseTable, instanceId) {
      try {
        await axios.delete(`/api/${databaseTable}/${instanceId}`);
        await this.fetchDatabaseData(databaseTable);
      } catch (error) {
        console.error(
          `Error deleting ${databaseTable}: ${instanceId} - `,
          error
        );
      }
    },
    hideComponents() {
      this.show = false;
      return new Promise((resolve) => setTimeout(resolve, 500));
    },
    showComponents() {
      this.show = true;
      return new Promise((resolve) => setTimeout(resolve, 0));
    },
  },
});
