<template>
  <Chart :data="chartData" :options="chartOptions" type="line" />
  <div class="mt-2 flex justify-content-center">
    <Button
      :label="$t(`reset_colors`).toLocaleUpperCase()"
      rounded
      size="small"
      @click="resetColors"
    />
  </div>
</template>

<script setup>
import { onBeforeMount, onMounted, ref } from "vue";
import axios from "@/services/axios";

import lv_translation from "@/locales/primevue/lv.json";

let seminarCount = ref();
let competitionCount = ref();

const chartData = ref({
  labels: lv_translation.monthNamesShort,
  datasets: [],
});
const chartOptions = ref();

function resetColors() {
  for (let dataset of chartData.value.datasets) {
    const tempResetColor = getRandomRedsOrangesYellows();
    dataset.borderColor = `rgb(${tempResetColor})`;
    dataset.backgroundColor = `rgba(${tempResetColor}, 0.2)`;
  }
}

function getRandomRedsOrangesYellows() {
  const red = 255;
  const green = Math.floor(Math.random() * 256); // Between red and yellow
  const blue = Math.floor(Math.random() * 100); // Make orange color possible too

  return `${red},${green},${blue}`;
}

onMounted(() => {
  chartOptions.value = setChartOptions();
});

onBeforeMount(async () => {
  const documentStyle = getComputedStyle(document.documentElement);

  const response = await axios.get(`/api/event_count_by_month`);
  for (const [eventName, data] of Object.entries(response.data)) {
    const color = getRandomRedsOrangesYellows();
    chartData.value.datasets.push({
      label: eventName,
      data: [...Object.values(data[0])], // Prepend null to shift data indices
      tension: 0.4,
      borderColor: `rgb(${color})`,
      fill: true,
      backgroundColor: `rgba(${color}, 0.2)`,
    });
  }
});

const setChartOptions = () => {
  const documentStyle = getComputedStyle(document.documentElement);
  const textColor = documentStyle.getPropertyValue("--text-color");
  const textColorSecondary = documentStyle.getPropertyValue(
    "--text-color-secondary"
  );
  const surfaceBorder = documentStyle.getPropertyValue("--surface-border");

  return {
    maintainAspectRatio: false,
    aspectRatio: 0.6,
    plugins: {
      datalabels: {
        display: false,
      },
      legend: {
        labels: {
          color: textColor,
        },
      },
    },
    scales: {
      x: {
        ticks: {
          color: textColorSecondary,
        },
        grid: {
          color: surfaceBorder,
        },
      },
      y: {
        ticks: {
          color: textColorSecondary,
        },
        grid: {
          color: surfaceBorder,
        },
      },
    },
  };
};
</script>

<style scoped></style>
