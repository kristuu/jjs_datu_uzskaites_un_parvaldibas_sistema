<template>
  <Transition name="fade" mode="out-in">
    <div class="container-xl" v-if="useFetchDataStore().show">
      <div>
        <div class="grid">
          <div class="col-3">
            <div class="grid">
              <div class="col-12">
                <div class="p-2 bg-white rounded shadow text-center">
                  <h1 class="fw-normal m-0 text-primary">{{ date }}</h1>
                  <h1 class="fw-bold m-0 text-primary">{{ time }}</h1>
                </div>
              </div>
              <div class="col-12 h-full">
                <div class="p-2 bg-white rounded shadow text-center h-full">
                  <h1>{{ temperature }}</h1>
                </div>
              </div>
            </div>
          </div>
          <div class="col-9">
            <div class="grid">
              <div class="col-6">
                <div class="p-3 bg-white rounded shadow h-full">
                  <div class="flex justify-content-between align-items-center">
                    <h2 class="m-0">SACENSĪBAS</h2>
                    <Button icon="bi bi-pencil" rounded text outlined />
                  </div>
                  <Divider class="mt-2" />
                  <Timeline :value="events">
                    <template #opposite="slotProps">
                      <small class="p-text-secondary block">{{slotProps.item.date}}</small>
                    </template>
                    <template #content="slotProps">
                      <div class="mb-4">
                        <p class="m-0"><strong>{{slotProps.item.name}}</strong></p>
                        <p class="m-0">{{slotProps.item.discipline}}</p>
                      </div>
                    </template>
                  </Timeline>
                </div>
              </div>
              <div class="col-6">
                <div class="p-3 bg-white rounded shadow h-full">
                  <div class="flex justify-content-between align-items-center">
                    <h2 class="m-0">SEMINĀRI</h2>
                    <Button icon="bi bi-pencil" rounded text outlined />
                  </div>
                  <Divider class="mt-2" />
                  <Timeline :value="seminars">
                    <template #opposite="slotProps">
                      <small class="p-text-secondary block">{{slotProps.item.date}}</small>
                    </template>
                    <template #content="slotProps">
                      <div class="mb-4">
                        <p class="m-0"><strong>{{slotProps.item.name}}</strong></p>
                        <p class="m-0">{{slotProps.item.discipline}}</p>
                      </div>
                    </template>
                  </Timeline>
                </div>
              </div>
            </div>
          </div>
          <div class="col-12">
            <div class="p-3 bg-primary rounded shadow">
              <h2 class="m-0">STATISTIKA</h2>
            </div>
          </div>
        </div>
      </div>
    </div>
  </Transition>
</template>

<script setup>
import {computed, onMounted, onUnmounted, ref} from "vue";
import {useFetchDataStore} from "@/stores/fetchDataStore";
import axios from "@/services/axios";

let time = ref(new Date().toLocaleTimeString());
let timer = null;

let date = ref(new Date().toLocaleDateString());

let weatherData = ref(null);
let temperature = computed(() => weatherData.current?.temp_c);

const fetchData = async () => {
  const response = await axios.get(`http://api.weatherapi.com/v1/current.json?key=86d289e2d3904b04bcf133418241604&q=Incukalns&aqi=no`);
  data.value = response.data;
}

const events = ref([
  {
    discipline: "Konkūrs / Show jumping",
    name: "LJF kauss konkūrā 2.posms",
    date: "11.05. - 12.05."
  },
  {
    discipline: "Konkūrs / Show jumping",
    name: "CSI2*/ CSI1*",
    date: "06.06. - 09.06."
  },
  {
    discipline: "Pajūgu braukšana / Driving",
    name: "CAI2*-H1 CAI2*-P1",
    date: "06.07. - 07.07."
  }
]);

const seminars = ref([
  {
    discipline: "Vispārīgs / General",
    name: "Zirgu psiholoģija",
    date: "24.04."
  },
  {
    discipline: "Vispārīgs / General",
    name: "Seminārs ar Anci Polāni",
    date: "27.04."
  },
  {
    discipline: "Konkūrs / Show jumping",
    name: "Equestrian Forum",
    date: "04.05. - 05.05."
  }
]);

onMounted(() => {
  timer = setInterval(() => {
    time.value = new Date().toLocaleTimeString();
  }, 1000);

  setInterval(fetchData, 1000);

  useFetchDataStore().showComponents();
});

onUnmounted(() => {
  clearInterval(timer);
});
</script>

<style scoped>

</style>