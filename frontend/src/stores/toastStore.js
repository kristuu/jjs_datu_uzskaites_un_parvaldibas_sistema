import { defineStore } from 'pinia';

export const useToastStore = defineStore({
   id: "toast",
    state: () => ({
        toastInstance: null
    })
});