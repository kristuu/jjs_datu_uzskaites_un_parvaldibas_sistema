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
                    {{ $t(`home`).toLocaleUpperCase() }}
                  </h1>
                </div>
              </div>
            </div>
          </div>
          <div class="col-12 lg:col-9">
            <div class="grid">
              <div class="col-12">
                <div class="p-3 bg-white rounded shadow h-full">
                  <div
                    class="flex flex-column md:flex-row justify-content-between align-items-center"
                  >
                    <h2 class="m-0 text-primary">
                      {{ $t(`your_reservations`).toLocaleUpperCase() }}
                    </h2>
                    <Button class="mt-2 md:m-0" icon="bi bi-plus-lg" outlined />
                  </div>
                  <Divider class="my-3" />
                </div>
              </div>
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

const seminarsPercentage = ref(0);
const competitionsPercentage = ref(0);

const competitions = ref([]);
const seminars = ref([]);

onBeforeMount(async () => {
  useFetchDataStore().isFetching = true;
  await axios
    .get("/api/upcoming_events")
    .then((response) => {
      console.log(response);
      competitions.value = response.data.competitions;
      seminars.value = response.data.seminars;
    })
    .catch((error) => {
      console.error(error);
    })
    .finally(() => {
      useFetchDataStore().isFetching = false;
    });

  await axios
    .get(`/api/event_percentage`)
    .then((response) => {
      console.log(response);
      seminarsPercentage.value = response.data.seminars;
      competitionsPercentage.value = response.data.competitions;
    })
    .catch((error) => {
      console.error(error);
    })
    .finally(() => {
      useFetchDataStore().showComponents();
    });
});

onMounted(() => {
  timer = setInterval(() => {
    time.value = new Date().toLocaleTimeString();
  }, 1000);
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
