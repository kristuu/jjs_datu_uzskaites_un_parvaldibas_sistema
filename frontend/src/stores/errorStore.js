import router from "@/router/router.js";
import { defineStore } from "pinia";

export const useErrorStore = defineStore({
    id: "error",
    state: () => ({
        errorStatus: null,
        errorMessage: "",
    }),
    actions: {
        setErrorStatus(payload) {
            this.errorStatus = payload;
        },
        setErrorMessage(payload) {
            this.errorMessage = payload;
        },
        resetError() {
            this.errorStatus = null;
            this.errorMessage = "";
        },
        async displayError(error) {
            this.resetError();
            this.setErrorStatus(error.status);
            this.setErrorMessage(error.data.message);
            try {
                await router.push({ name: "ErrorView" });
            } catch (routerError) {
                console.error("Router error from displayError: ", routerError);
            }
        }
    }
});