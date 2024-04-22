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
                  <Timeline :value="competitions">
                    <template #opposite="slotProps">
                      <small class="hidden lg:block p-text-secondary">{{
                        slotProps.item.start + ` - ` + slotProps.item.end
                      }}</small>
                    </template>
                    <template #content="slotProps">
                      <div class="mb-4">
                        <small class="p-text-secondary block lg:hidden">{{
                          slotProps.item.start + ` - ` + slotProps.item.end
                        }}</small>
                        <p class="m-0">
                          <strong>{{ slotProps.item.name }}</strong>
                        </p>
                        <p class="m-0">
                          {{ slotProps.item.event_category.name }}
                        </p>
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
                        slotProps.item.start + ` - ` + slotProps.item.end
                      }}</small>
                    </template>
                    <template #content="slotProps">
                      <div class="mb-4">
                        <small class="p-text-secondary block lg:hidden">{{
                          slotProps.item.start + ` - ` + slotProps.item.end
                        }}</small>
                        <p class="m-0">
                          <strong>{{ slotProps.item.name }}</strong>
                        </p>
                        <p class="m-0">
                          {{ slotProps.item.event_category.name }}
                        </p>
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
import { computed, onBeforeMount, onMounted, onUnmounted, ref } from "vue";
import { useFetchDataStore } from "@/stores/fetchDataStore";
import axios from "@/services/axios";

let time = ref(new Date().toLocaleTimeString());
let timer = null;

let date = ref(new Date().toLocaleDateString());

const events = ref([]);

const competitions = computed(() =>
  events.value
    .filter((event) => event.event_type.name === `Sacensības`)
    .slice(0, 3)
);
const seminars = computed(() =>
  events.value
    .filter((event) => event.event_type.name === `Semināri`)
    .slice(0, 3)
);

onBeforeMount(async () => {
  useFetchDataStore().isFetching = true;
  await axios
    .get("/api/events")
    .then((response) => {
      events.value = response.data.instances;
      console.log(response);
    })
    .catch((e) => {
      console.log(e.response.data);
    })
    .finally(() => {
      useFetchDataStore().isFetching = false;
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
