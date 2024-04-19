<template>
  <Transition mode="out-in" name="fade">
    <div v-if="useFetchDataStore().show" class="container-xl mt-3 lg:mt-0">
      <div>
        <div class="grid">
          <div class="col-12 lg:col-3">
            <div class="grid">
              <div class="col-12 h-full">
                <div
                  class="p-2 bg-primary lg:bg-white rounded shadow text-center h-full"
                >
                  <h1 class="m-0 text-white lg:text-primary">
                    {{ `Admin centrs`.toLocaleUpperCase() }}
                  </h1>
                </div>
              </div>
              <div class="col-12 h-full">
                <div class="p-2 bg-white rounded shadow text-center">
                  <h1 class="fw-normal m-0 text-primary">{{ date }}</h1>
                  <h1 class="fw-bold m-0 text-primary">{{ time }}</h1>
                </div>
              </div>
            </div>
          </div>
          <div class="col-12 lg:col-9">
            <div class="grid">
              <div class="col-12 sm:col-6">
                <div class="p-3 bg-white rounded shadow h-full">
                  <div class="flex justify-content-between align-items-center">
                    <h2 class="m-0">SACENSĪBAS</h2>
                    <Button icon="bi bi-pencil" outlined rounded text />
                  </div>
                  <Divider class="mt-2" />
                  <Timeline :value="events">
                    <template #opposite="slotProps">
                      <small class="hidden lg:block p-text-secondary">{{
                        slotProps.item.date
                      }}</small>
                    </template>
                    <template #content="slotProps">
                      <div class="mb-4">
                        <small class="p-text-secondary block lg:hidden">{{
                          slotProps.item.date
                        }}</small>
                        <p class="m-0">
                          <strong>{{ slotProps.item.name }}</strong>
                        </p>
                        <p class="m-0">{{ slotProps.item.discipline }}</p>
                      </div>
                    </template>
                  </Timeline>
                </div>
              </div>
              <div class="col-12 sm:col-6">
                <div class="p-3 bg-white rounded shadow h-full">
                  <div class="flex justify-content-between align-items-center">
                    <h2 class="m-0">SEMINĀRI</h2>
                    <Button icon="bi bi-pencil" outlined rounded text />
                  </div>
                  <Divider class="mt-2" />
                  <Timeline :value="seminars">
                    <template #opposite="slotProps">
                      <small class="hidden lg:block p-text-secondary">{{
                        slotProps.item.date
                      }}</small>
                    </template>
                    <template #content="slotProps">
                      <div class="mb-4">
                        <small class="p-text-secondary block lg:hidden">{{
                          slotProps.item.date
                        }}</small>
                        <p class="m-0">
                          <strong>{{ slotProps.item.name }}</strong>
                        </p>
                        <p class="m-0">{{ slotProps.item.discipline }}</p>
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
import { onBeforeMount, onMounted, onUnmounted, ref } from "vue";
import { useFetchDataStore } from "@/stores/fetchDataStore";
import axios from "@/services/axios";

let time = ref(new Date().toLocaleTimeString());
let timer = null;

let date = ref(new Date().toLocaleDateString());

const events = ref([]);

const seminars = ref([
  {
    discipline: "Vispārīgs / General",
    name: "Zirgu psiholoģija",
    date: "24.04.",
  },
  {
    discipline: "Vispārīgs / General",
    name: "Seminārs ar Anci Polāni",
    date: "27.04.",
  },
  {
    discipline: "Konkūrs / Show jumping",
    name: "Equestrian Forum",
    date: "04.05. - 05.05.",
  },
]);

onBeforeMount(async () => {
  await axios
    .get("/api/events")
    .then((response) => {
      events.value = response.data;
      console.log(response);
    })
    .catch((e) => {
      console.log(`AAAAAAAAAAAAA:`, e.response.data);
    });
});

onMounted(() => {
  timer = setInterval(() => {
    time.value = new Date().toLocaleTimeString();
  }, 1000);

  useFetchDataStore().showComponents();
});

onUnmounted(() => {
  clearInterval(timer);
});
</script>

<style scoped>
.p-timeline-event-opposite {
  display: none;
}
</style>
