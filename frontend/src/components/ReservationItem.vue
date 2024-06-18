<template>
  <div
    :class="{ 'border-top-1 surface-border': index !== 0 }"
    class="flex flex-column sm:flex-row p-3 gap-3 w-full"
  >
    <div class="w-10rem md:w-7rem align-self-center relative">
      <img
        :src="reservation.instructor.user.profile_picture"
        class="block xl:block mx-auto border-round w-full"
        style="border-radius: 0.375rem 0 0"
      />
      <Tag
        v-if="!isLoading"
        :severity="statusSeverity"
        :value="statusText"
        class="absolute"
        style="left: 4px; top: 4px"
      />
    </div>
    <div
      class="flex flex-column md:flex-row justify-content-between flex-1 gap-4"
    >
      <div
        class="flex flex-row md:flex-column justify-content-between align-items-start gap-2"
      >
        <div>
          <div v-if="isLoading" class="w-100">
            <Skeleton height="1rem" width="100px" />
          </div>
          <span v-else class="font-medium text-secondary text-sm">{{
            reservation.instructor.certificate.category.name
          }}</span>
          <div v-if="isLoading" class="w-100">
            <Skeleton class="mt-2" height="2rem" width="100%" />
          </div>
          <div v-else class="text-lg font-medium text-900 mt-2">
            {{
              `${reservation.instructor.user.name} ${reservation.instructor.user.surname}`
            }}
          </div>
        </div>
        <div>
          <div v-if="isLoading" class="w-100">
            <Skeleton height="1.5rem" width="136px" />
          </div>
          <div
            v-else
            class="surface-100 p-1 w-full"
            style="border-radius: 0.375rem"
          >
            <div
              class="surface-0 flex align-items-center gap-1 justify-content-center py-1 px-3"
              style="
                border-radius: 0.375rem;
                box-shadow: 0px 1px 2px 0px rgba(0, 0, 0, 0.04),
                  0px 1px 2px 0px rgba(0, 0, 0, 0.06);
              "
            >
              <template v-for="star in 5">
                <i
                  v-if="star <= reservation.instructor.rating"
                  :key="'filled-' + star"
                  class="pi pi-star-fill text-primary"
                ></i>
                <i
                  v-else
                  :key="'empty-' + star"
                  class="pi pi-star text-primary"
                ></i>
              </template>
            </div>
          </div>
        </div>
      </div>
      <div class="flex flex-column justify-content-between md:align-items-end">
        <div v-if="isLoading" class="w-100">
          <Skeleton height="2rem" width="75%" />
          <Skeleton class="mt-2" height="2rem" width="100%" />
        </div>
        <div v-else>
          <div class="text-sm fw-bold text-black">
            {{ t("date") }}
            <br />
            <span class="text-xl font-semibold text-900">{{
              formatDate(reservation.instructor_availability.start_time)
            }}</span>
          </div>
          <div class="mt-2 text-sm fw-bold text-black">
            {{ t("time") }}
            <br />
            <span class="text-xl font-semibold text-900">{{
              formatTimeRange(
                reservation.instructor_availability.start_time,
                reservation.instructor_availability.end_time
              )
            }}</span>
          </div>
        </div>
        <div class="flex flex-row gap-2 mt-2 flex-wrap">
          <Button
            v-if="isEligibleForPDF(reservation)"
            :label="$t(`print_pdf`).toLocaleUpperCase()"
            class="button-auto-width"
            size="small"
            @click="generatePDF(reservation)"
          />
          <Button
            v-if="
              !['accepted', 'denied', 'cancelled'].includes(reservation.status)
            "
            :label="t('reservations.cancel').toLocaleUpperCase()"
            class="button-auto-width"
            outlined
            size="small"
            text
            @click="emitCancel($event, reservation)"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from "vue";
import Skeleton from "primevue/skeleton";
import Tag from "primevue/tag";
import Button from "primevue/button";
import { useI18n } from "vue-i18n";
import axios from "@/services/axios";
import { format, parseISO } from "date-fns";
import { useFetchDataStore } from "@/stores/fetchDataStore";

const { t } = useI18n();
const emit = defineEmits([
  "reservation-cancelled",
  "close-all-reservations-open-cancel",
]);

const props = defineProps({
  reservation: Object,
  index: Number,
});

const fetchDataStore = useFetchDataStore();

let isLoading = computed(() => fetchDataStore.isFetching);

const statusSeverity = computed(() => {
  return props.reservation.status === "submitted"
    ? "info"
    : props.reservation.status === "accepted"
    ? "success"
    : props.reservation.status === "denied"
    ? "danger"
    : "secondary";
});

const statusText = computed(() => {
  return t(`reservations.${props.reservation.status}`).toLocaleUpperCase();
});

const formatDate = (dateString) => {
  const date = parseISO(dateString);
  return format(date, "dd.MM.yyyy");
};

const formatTimeRange = (startTimeString, endTimeString) => {
  const startTime = parseISO(startTimeString);
  const endTime = parseISO(endTimeString);

  const formattedStartTime = format(startTime, "HH.mm");
  const formattedEndTime = format(endTime, "HH.mm");

  return `${formattedStartTime} - ${formattedEndTime}`;
};

const isEligibleForPDF = (reservation) => {
  return ["accepted", "submitted"].includes(reservation.status);
};

const generatePDF = async (reservation) => {
  try {
    const response = await axios.get(`/api/reservation/pdf/${reservation.id}`, {
      responseType: "blob",
    });
    const blob = new Blob([response.data], { type: "application/pdf" });
    const blobUrl = URL.createObjectURL(blob);
    const newWindow = window.open(blobUrl, "_blank");
    if (newWindow) {
      newWindow.onload = () => {
        newWindow.print();
      };
    } else {
      console.error("Failed to open new window.");
    }
  } catch (error) {
    console.error("Error generating PDF:", error);
  }
};

const emitCancel = async (event, reservation) => {
  emit("close-all-reservations-open-cancel", reservation);
};
</script>

<style scoped>
.button-auto-width {
  flex-shrink: 1;
  flex-grow: 1;
  white-space: nowrap;
}

.flex-wrap {
  flex-wrap: wrap;
}
</style>
