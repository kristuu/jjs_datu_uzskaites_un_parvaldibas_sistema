<template>
  <div class="bg-white rounded shadow p-3">
    <div
      class="flex flex-column md:flex-row justify-content-between align-items-center"
    >
      <h2 class="m-0 text-primary text-center md:text-left">
        {{ `Pie tevis pieteiktās rezervācijas`.toLocaleUpperCase() }}
      </h2>
    </div>
    <Divider class="my-3" />
    <div>
      <div
        v-if="reservations.length === 0 && !fetchDataStore.isFetching"
        class="text-center"
      >
        <p class="text-primary fw-bold mb-0">
          Nav aktīvu rezervāciju pieteikumu
        </p>
        <p class="text-primary mt-0">
          Pārliecinies, vai esi aktualizējis sev pieejamos laikus savā trenera
          profilā
        </p>
      </div>
      <div v-if="fetchDataStore.isFetching">
        <div class="flex flex-column sm:flex-row p-3 gap-3 w-full">
          <div class="w-10rem md:w-7rem align-self-center relative">
            <Skeleton
              class="block xl:block mx-auto border-round w-full"
              height="10rem"
            />
          </div>
          <div
            class="flex flex-column md:flex-row justify-content-between flex-1 gap-4"
          >
            <div
              class="flex flex-row md:flex-column justify-content-between align-items-start gap-2"
            >
              <div>
                <Skeleton height="1rem" width="100px" />
                <Skeleton class="mt-2" height="2rem" width="100%" />
                <Skeleton class="mt-2" height="1.5rem" width="75%" />
              </div>
              <div>
                <Skeleton height="1.5rem" width="136px" />
                <Skeleton class="mt-2" height="2rem" width="100%" />
              </div>
            </div>
            <div
              class="flex flex-column justify-content-between md:align-items-end"
            >
              <Skeleton height="2rem" width="75%" />
              <Skeleton class="mt-2" height="2rem" width="100%" />
              <div class="flex flex-row gap-2 mt-2 flex-wrap">
                <Skeleton
                  class="button-auto-width"
                  height="2rem"
                  width="100px"
                />
                <Skeleton
                  class="button-auto-width"
                  height="2rem"
                  width="100px"
                />
              </div>
            </div>
          </div>
        </div>
      </div>
      <InstructorReservationItem
        v-for="(item, index) in reservations"
        v-if="!fetchDataStore.isFetching"
        :key="index"
        :index="index"
        :isLoading="fetchDataStore.isFetching"
        :reservation="item"
        class="w-full"
        @reservation-cancelled="fetchReservations"
        @close-all-reservations-open-cancel="
          (reservation) => {
            closeAllReservationsOpenCancel(reservation);
          }
        "
      />
    </div>
    <Divider class="m-0" />
    <div class="text-end">
      <Button
        class="mt-3 w-full sm:w-auto"
        label="SKATĪT VISAS"
        size="small"
        @click="$emit('showAllInstructorsReservations')"
      >
      </Button>
    </div>
  </div>
</template>

<script setup>
import { useFetchDataStore } from "@/stores/fetchDataStore";
import Button from "primevue/button";
import Divider from "primevue/divider";
import axios from "@/services/axios";

const props = defineProps({
  reservations: Array,
});

const emit = defineEmits([
  "showAllInstructorsReservations",
  "close-all-reservations-open-cancel",
]);
const fetchDataStore = useFetchDataStore();

const fetchReservations = async () => {
  try {
    fetchDataStore.setIsFetching(true);
    const response = await axios.get("/api/instructorReservations");
    emit("update:reservations", response.data);
  } catch (error) {
    console.error("Error fetching reservations:", error);
  } finally {
    fetchDataStore.setIsFetching(false);
  }
};

const closeAllReservationsOpenCancel = (reservation) => {
  emit("close-all-reservations-open-cancel", reservation);
};
</script>

<style scoped></style>
