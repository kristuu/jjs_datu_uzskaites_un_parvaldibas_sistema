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
        },
        formatDatesOnInstance(instance) {
            Object.keys(instance.value).forEach((key) => {
                if (key === 'birthdate') {
                    let date;

                    if (instance.value[key] instanceof Date) {
                        date = instance.value[key];
                    } else {
                        // Parse time in the 'dd.mm.yyyy' format
                        let parts = instance.value[key].split(".");
                        date = new Date(parts[2], parts[1] - 1, parts[0]);
                    }

                    let year = date.getFullYear();
                    let month = (date.getMonth() + 1).toString().padStart(2, '0'); // Ensure month is 2 digits
                    let day = date.getDate().toString().padStart(2, '0'); // Ensure day is 2 digits

                    instance.value[key] = `${year}-${month}-${day}`;
                }
            });
        }
    }
});