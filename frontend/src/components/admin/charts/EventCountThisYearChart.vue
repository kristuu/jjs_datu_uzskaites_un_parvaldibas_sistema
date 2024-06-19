<template>
  <Chart
    :data="proportionsChartData"
    :options="proportionsChartOptions"
    type="pie"
  />
  <!--  <div class="mt-2 flex justify-content-center">
      <Button
        :label="$t(`reset_colors`).toLocaleUpperCase()"
        rounded
        size="small"
        @click="resetColors"
      />
    </div>-->
</template>

<script setup>
import { onBeforeMount, onMounted, ref } from "vue";
import axios from "@/services/axios";

const proportionsChartData = ref({
  labels: ["Sacensības / Competitions", "Semināri / Seminars"],
  datasets: [
    {
      data: [],
    },
  ],
});
const proportionsChartOptions = ref();

function resetColors() {
  proportionsChartData.value.datasets[0].backgroundColor = [
    `rgb(${getRandomRedsOrangesYellows()})`,
    `rgb(${getRandomRedsOrangesYellows()})`,
  ];
}

function getRandomRedsOrangesYellows() {
  const red = 255;
  const green = Math.floor(Math.random() * 256); // Between red and yellow
  const blue = Math.floor(Math.random() * 100); // Make orange color possible too

  return `${red},${green},${blue}`;
}

onMounted(() => {
  proportionsChartOptions.value = setChartOptions();
});

onBeforeMount(async () => {
  const documentStyle = getComputedStyle(document.documentElement);

  const response = await axios.get(`/api/event_count_year`);
  proportionsChartData.value.datasets[0].data = [
    response.data.competitions,
    response.data.seminars,
  ];

  proportionsChartData.value.datasets[0].backgroundColor = [`orange`, `red`];
});

const setChartOptions = () => {
  const documentStyle = getComputedStyle(document.documentElement);
  const textColor = documentStyle.getPropertyValue("--text-color");

  return {
    aspectRatio: 2,
    plugins: {
      datalabels: {
        color: "black",
        labels: {
          title: {
            font: {
              weight: "bold",
            },
          },
        },
      },
      legend: {
        labels: {
          usePointStyle: true,
          color: textColor,
        },
      },
    },
  };
};
</script>

<style scoped></style>
