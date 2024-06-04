<template>
  <div class="bg-white rounded shadow p-3">
    <div
      class="flex flex-column md:flex-row justify-content-between align-items-center"
    >
      <h2 class="m-0 text-primary">
        {{ $t("your_reservations").toLocaleUpperCase() }}
      </h2>
      <Button
        class="mt-2 md:m-0"
        icon="bi bi-plus-lg"
        outlined
        @click="$router.push({ name: 'BookingList' })"
      />
    </div>
    <Divider class="my-3" />
    <div>
      <ReservationItem
        v-for="(item, index) in reservations"
        :key="index"
        :index="index"
        :isLoading="isFetching"
        :reservation="item"
        class="w-full mb-3"
        @reservation-cancelled="fetchReservations"
      />
    </div>
    <Divider class="m-0" />
    <div class="text-end">
      <Button class="mt-3" @click="$emit('showAllReservations')"
        >VISAS REZERVĀCIJAS
      </Button>
    </div>
  </div>
</template>

<script setup>
import { useFetchDataStore } from "@/stores/fetchDataStore";
import Button from "primevue/button";
import Divider from "primevue/divider";
import ReservationItem from "./ReservationItem.vue";
import axios from "@/services/axios";

const props = defineProps({
  reservations: Array,
});

const emit = defineEmits(["showAllReservations"]);
const fetchDataStore = useFetchDataStore();

const fetchReservations = async () => {
  try {
    fetchDataStore.setIsFetching(true);
    const response = await axios.get("/api/homeData");
    emit("update:reservations", response.data);
  } catch (error) {
    console.error("Error fetching reservations:", error);
  } finally {
    fetchDataStore.setIsFetching(false);
  }
};
</script>

<style scoped></style>
