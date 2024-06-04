import { defineStore } from "pinia";

const keys = ["birthdate", "job_start_date", "expiration_date"];

export const useDateStore = defineStore({
  id: "date",
  state: () => ({}),
  actions: {
    parseLVstringDate(dateString) {
      if (!dateString || typeof dateString !== "string") {
        return new Date(NaN); // Return an invalid date if the input is not valid
      }
      const [day, month, year] = dateString.split(".");
      return new Date(
        parseInt(year, 10),
        parseInt(month, 10) - 1,
        parseInt(day, 10)
      );
    },
    formatDatesOnInstance(instance) {
      if (instance) {
        Object.keys(instance).forEach((key) => {
          if (keys.includes(key)) {
            try {
              let date;

              if (instance[key] instanceof Date) {
                date = instance[key];
              } else {
                // Parse time in the 'dd.mm.yyyy' format
                let parts = instance[key].split(".");
                date = new Date(parts[2], parts[1] - 1, parts[0]);
              }

              let year = date.getFullYear();
              let month = (date.getMonth() + 1).toString().padStart(2, "0"); // Ensure month is 2 digits
              let day = date.getDate().toString().padStart(2, "0"); // Ensure day is 2 digits

              instance[key] = `${year}-${month}-${day}`;
            } catch (e) {
              console.error("Date formatting error: ", e);
            }
          }
        });
      }
    },
  },
});
