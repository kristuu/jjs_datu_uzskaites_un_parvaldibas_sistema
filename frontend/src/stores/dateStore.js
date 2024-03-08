import axios from "@/services/axios.js";
import router from "@/router/router.js";
import { defineStore } from "pinia";

export const useDateStore = defineStore({
    id: "date",
    state: () => ({
    }),
    actions: {
        parseLVstringDate(dateString) {
            const [day, month, year] = dateString.split('.');
            return new Date(parseInt(year, 10), parseInt(month, 10) - 1, parseInt(day, 10));
        }
    }
});