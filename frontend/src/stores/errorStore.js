import router from "@/router/router.js";
import { defineStore } from "pinia";

export const useErrorStore = defineStore({
    id: "error",
    state: () => ({
        errorStatus: null,
        errorMessage: "",
        errorList: {},
        mainLoginError: ""
    }),
    actions: {
        setMainLoginError(payload) {
            this.mainLoginError = payload;
        },
        setErrorStatus(payload) {
            this.errorStatus = payload;
        },
        setErrorMessage(payload) {
            this.errorMessage = payload;
        },
        setErrorList(payload) {
            this.errorList = payload;
        },
        resetMainLoginError() {
            this.mainLoginError = "";
        },
        resetError() {
            this.errorStatus = null;
            this.errorMessage = "";
        },
        resetErrorList() {
            this.errorList = {};
        },
        async displayError(error) {
            this.resetError();
            this.setErrorStatus(error.status);
            this.setErrorMessage(error);
            try {
                await router.push({ name: "ErrorView" });
            } catch (routerError) {
                console.error("Router error from displayError: ", routerError);
            }
        }
    }
});