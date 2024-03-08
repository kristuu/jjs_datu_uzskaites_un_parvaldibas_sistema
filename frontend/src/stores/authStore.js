import axios from "@/services/axios.js";
import router from "@/router/router.js";
import { defineStore } from "pinia";

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
                const response = await axios.get("/user");
                if (response.data) {
                    this.user = response.data;
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
                const response = await axios.get("/get-permissions");
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
        async logout() {
            try {
                await axios.get("/logout");
                this.authorized = false;
                this.user = null;
            } catch (error) {
                console.error("Error occurred on logging out: ", error);
            } finally {
                await router.push({ name: "LoginPage" });
            }
        }
    }
});