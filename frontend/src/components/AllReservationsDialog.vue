<template>
  <Dialog
    :pt="{ root: 'border-none', mask: { style: 'backdrop-filter: blur(4px)' } }"
    :visible="visible"
    class="container-lg"
    dismissableMask
    modal
    position="top"
    style="background: none"
    @update:visible="updateVisible"
  >
    <template #container="{ closeCallback }">
      <div class="grid mt-2">
        <div class="col-12">
          <div class="bg-primary rounded text-center shadow">
            <h1 class="py-4 m-0">VISAS REZERVĀCIJAS</h1>
          </div>
        </div>
        <div class="col-12">
          <div class="bg-white rounded p-3">
            <div class="flex justify-content-between">
              <div class="flex-column sm:flex-row gap-2">
                <Dropdown
                  v-model="selectedStatus"
                  :options="statusOptions"
                  class="mr-2"
                  optionLabel="name"
                  optionValue="value"
                  placeholder="Izvēlies statusu"
                />
                <InputText
                  v-model="searchQuery"
                  class="mt-2 sm:mt-0"
                  placeholder="Meklē pēc satura"
                />
              </div>
              <Button
                class="fs-2 p-0 mb-2 float-end"
                icon="bi bi-x-lg"
                text
                @click="closeCallback"
              />
            </div>
            <Divider class="my-3" />
            <DataView
              :value="filteredReservations"
              style="max-height: 65vh; overflow: hidden auto"
            >
              <template #list="slotProps">
                <div class="grid grid-nogutter">
                  <ReservationItem
                    v-for="(item, index) in slotProps.items"
                    :key="index"
                    :index="index"
                    :isLoading="isLoading"
                    :reservation="item"
                    @close-all-reservations-open-cancel="
                      (reservation) => {
                        closeAllReservationsOpenCancel(reservation);
                      }
                    "
                  />
                </div>
              </template>
              <template v-if="isLoading" #empty>
                <div class="grid grid-nogutter">
                  <div v-for="i in 2" :key="i" class="col-12">
                    <div class="flex flex-column sm:flex-row p-3 gap-3">
                      <div class="md:w-7rem relative">
                        <Skeleton
                          height="10rem"
                          style="border-radius: 0.375rem 0.375rem 0 0"
                          width="7rem"
                        />
                      </div>
                      <div
                        class="flex flex-column md:flex-row justify-content-between flex-1 gap-4"
                      >
                        <div
                          class="flex flex-row md:flex-column justify-content-between align-items-start gap-2"
                        >
                          <div class="w-100">
                            <Skeleton height="1rem" width="100px" />
                            <Skeleton class="mt-2" height="2rem" width="100%" />
                          </div>
                          <div class="w-100">
                            <Skeleton height="1.5rem" width="136px" />
                          </div>
                        </div>
                        <div
                          class="flex flex-column justify-content-between md:align-items-end gap-5"
                        >
                          <div class="w-100">
                            <Skeleton height="2rem" width="75%" />
                            <Skeleton class="mt-2" height="2rem" width="100%" />
                          </div>
                          <div class="flex flex-row-reverse md:flex-row gap-2">
                            <Skeleton
                              height="2rem"
                              style="min-width: 168px"
                              width="100%"
                            />
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </template>
            </DataView>
          </div>
        </div>
      </div>
    </template>
  </Dialog>
</template>

<script setup>
import Skeleton from "primevue/skeleton";
import Button from "primevue/button";
import Divider from "primevue/divider";
import DataView from "primevue/dataview";
import Dialog from "primevue/dialog";
import ReservationItem from "./ReservationItem.vue";
import { computed, ref } from "vue";
import { format, parseISO } from "date-fns";

const props = defineProps({
  reservations: Array,
  visible: Boolean,
  isLoading: Boolean,
});

const emit = defineEmits([
  "update:visible",
  "close-all-reservations-open-cancel",
]);

const selectedStatus = ref("");
const searchQuery = ref("");

const statusOptions = [
  { name: "Visi statusi", value: "" },
  { name: "Pieteikts", value: "submitted" },
  { name: "Apstiprināts", value: "accepted" },
  { name: "Noraidīts", value: "denied" },
  { name: "Atcelts", value: "cancelled" },
];

const formatDate = (isoDateString) =>
  format(parseISO(isoDateString), "dd.MM.yyyy");
const formatTime = (isoDateString) => format(parseISO(isoDateString), "HH.mm");

const filteredReservations = computed(() => {
  return props.reservations.filter((reservation) => {
    const matchesStatus =
      !selectedStatus.value || reservation.status === selectedStatus.value;

    // Format date and times for display and searching
    const formattedStartDate = formatDate(
      reservation.instructor_availability.start_time
    );
    const formattedStartTime = formatTime(
      reservation.instructor_availability.start_time
    );
    const formattedEndTime = formatTime(
      reservation.instructor_availability.end_time
    );
    const displayString = `${formattedStartDate} ${formattedStartTime} - ${formattedEndTime}`;

    // Include instructor name and category in the search string
    const fullSearchString = `${displayString} ${reservation.instructor.user.name.toLowerCase()} ${reservation.instructor.user.surname.toLowerCase()} ${reservation.instructor.certificate.category.name.toLowerCase()}`;

    // Check if the full search string contains the search query
    const matchesSearch =
      !searchQuery.value ||
      fullSearchString.includes(searchQuery.value.toLowerCase());

    return matchesStatus && matchesSearch;
  });
});

const updateVisible = (value) => {
  emit("update:visible", value);
};

const closeAllReservationsOpenCancel = (reservation) => {
  emit("close-all-reservations-open-cancel", reservation);
};
</script>

<style scoped></style>
