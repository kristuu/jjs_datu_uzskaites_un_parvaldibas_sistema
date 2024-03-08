import axios from "@/services/axios.js";
import {defineStore} from "pinia";
import {useDateStore} from "@/stores/dateStore";

export const useFetchDataStore = defineStore({
    id: "fetchData",
    state: () => ({
        instance: null,
        allInstances: null,
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
            this.allInstances = null;
        },
        resetInstance() {
            this.instance = null;
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
        async createInstance(databaseTable, instance) {
          const dateStore = useDateStore();
          this.setLoading(true);
          try {
              dateStore.formatDatesOnInstance(instance);
              await axios.post(`/${databaseTable}`, instance);
              this.resetInstance();
              await router.push(`/admin/${databaseTable}`);
          } catch (error) {
              toast.add()
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