<script>
import { format } from "date-fns";
import { lv } from "date-fns/locale";

const weekdaysNominative = {
  "0": "Svētdiena",
  "1": "Pirmdiena",
  "2": "Otrdiena",
  "3": "Trešdiena",
  "4": "Ceturtdiena",
  "5": "Piektdiena",
  "6": "Sestdiena"
};

export default {
  name: 'HomePage',
  data() {
    return {
      today: new Date(),
    };
  },
  computed: {
    formattedDate() {
      const formattedDay = format(this.today, 'i', { locale: lv });
      const formattedDate = format(this.today, 'dd.MM.yyyy.');
      return this.capitalize(`${weekdaysNominative[formattedDay]}, ${formattedDate}`);
    },
  },
  methods:{
    capitalize(value) {
      if (!value) return '';
      value = value.toString();
      return value.charAt(0).toUpperCase() + value.slice(1);
    }
  },
};
</script>


<template>
  <div class="container-xl">
    <h1 class="h1">{{ formattedDate }}</h1>
    <h3 class="h3">{{ $t("businessName") }}</h3>
  </div>
</template>


<style scoped>

</style>