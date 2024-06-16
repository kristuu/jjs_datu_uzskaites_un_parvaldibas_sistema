import axios from "@/services/axios.js";
import router from "@/router/router.js";
import { defineStore } from "pinia";
import { useFetchDataStore } from "@/stores/fetchDataStore";
import { useDateStore } from "@/stores/dateStore";
import { useErrorStore } from "@/stores/errorStore";

export const useAuthStore = defineStore({
  id: "auth",
  state: () => ({
    authorized: false,
    user: null,
    permissions: [],
    roles: [],
  }),
  actions: {
    async fetchUser() {
      try {
        const response = await axios.get("/api/user");
        if (response.data) {
          this.user = response.data;
          console.log(response.data);
          this.authorized = true;
        } else {
          this.user = null;
          this.authorized = false;
        }
      } catch (error) {
        this.user = null;
        this.authorized = false;
        console.error("Error occurred: ", error);
      }
    },
    async fetchPermissions() {
      try {
        await axios.get(`/sanctum/csrf-cookie`);
        const response = await axios.get("/api/get-permissions");
        this.permissions = response.data.permissions;
        this.roles = response.data.roles;
        window.Laravel.jsPermissions = response.data;
      } catch (error) {
        this.permissions = [];
        window.Laravel.jsPermissions = [];
        console.error("Failed to fetch permissions: ", error);
      }
    },
    async checkAuth() {
      await this.fetchUser();
      await this.fetchPermissions();
    },
    async register() {
      useErrorStore().resetErrorList();
      useErrorStore().resetMainLoginError();
      useFetchDataStore().setIsProcessing(true);
      try {
        await axios.get(`/sanctum/csrf-cookie`);
        useDateStore().formatDatesOnInstance(useFetchDataStore().instance);
        await axios.post("/api/register", useFetchDataStore().instance);
        useFetchDataStore().resetInstance();
        await router.push({ name: "LoginPage" });
      } catch (error) {
        if (error.response.status === 422) {
          useErrorStore().setErrorList(error.response?.data.errors);
        } else {
          await useErrorStore().displayError(error);
        }
      } finally {
        useFetchDataStore().setIsProcessing(false);
      }
    },
    async login() {
      useErrorStore().resetErrorList();
      useErrorStore().resetMainLoginError();
      useFetchDataStore().setIsProcessing(true);
      try {
        await axios.get(`/sanctum/csrf-cookie`);
        const response = await axios.post(
          `/api/login`,
          useFetchDataStore().instance
        );
        this.authorized = true;
        await this.checkAuth();
        useFetchDataStore().resetInstance();
        await router.push({ name: "HomePage" });
      } catch (error) {
        if (error.response.status === 422) {
          useErrorStore().setErrorList(error.response.data.errors);
        } else if (error.response.status === 401) {
          useErrorStore().setMainLoginError(error.response.data.message);
        } else if (error.response.status === 429) {
          useErrorStore().setMainLoginError(
            "Pārsniegts autorizācijas mēģinājumu limits. Uzgaidiet pirms atkārtota mēģinājuma."
          );
        } else {
          console.log(error);
          await useErrorStore().displayError(error);
        }
      } finally {
        useFetchDataStore().setIsProcessing(false);
      }
    },
    async logout() {
      try {
        await axios.get("/api/logout");
        this.authorized = false;
        this.user = null;
        this.permissions = null;
        this.roles = null;
        this.clearCookies();
      } catch (error) {
        console.error("Error occurred on logging out: ", error);
      } finally {
        await router.push({ name: "LoginPage" });
      }
    },
    clearCookies() {
      const cookies = document.cookie.split("; ");
      cookies.forEach((cookie) => {
        const eqPos = cookie.indexOf("=");
        const name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
        const paths = ["/"];
        const domains = [
          window.location.hostname,
          `.${window.location.hostname}`,
        ];
        paths.forEach((path) => {
          domains.forEach((domain) => {
            document.cookie = `${name}=;expires=Thu, 01 Jan 1970 00:00:00 GMT;path=${path};domain=${domain};secure;SameSite=None`;
            document.cookie = `${name}=;expires=Thu, 01 Jan 1970 00:00:00 GMT;path=${path};domain=${domain}`;
          });
        });
      });
    },
  },
});
