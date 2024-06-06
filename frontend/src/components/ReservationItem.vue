<template>
  <div
    :class="{ 'border-top-1 surface-border': index !== 0 }"
    class="flex flex-column sm:flex-row p-3 gap-3 w-full"
  >
    <div class="md:w-7rem relative">
      <img
        :src="reservation.instructor.user.profile_picture"
        class="block xl:block mx-auto border-round w-full"
        style="border-radius: 0.375rem 0.375rem 0 0"
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
      <div
        class="flex flex-column justify-content-between md:align-items-end gap-5"
      >
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
        <div
          v-if="!['accepted', 'denied'].includes(reservation.status)"
          class="flex flex-row-reverse md:flex-row gap-2"
        >
          <ConfirmPopup></ConfirmPopup>
          <Button
            :label="t('reservations.cancel').toLocaleUpperCase()"
            class="flex-auto md:flex-initial white-space-nowrap"
            outlined
            size="small"
            text
            @click="confirmCancel($event, reservation.id)"
          />
        </div>
        <Button
          v-if="isEligibleForPDF(reservation)"
          @click="generatePDF(reservation)"
          >{{ t("print_pdf") }}
        </Button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from "vue";
import { useToast } from "primevue/usetoast";
import { useConfirm } from "primevue/useconfirm";
import ConfirmPopup from "primevue/confirmpopup";
import Skeleton from "primevue/skeleton";
import Tag from "primevue/tag";
import Button from "primevue/button";
import { useI18n } from "vue-i18n";
import axios from "@/services/axios";
import moment from "moment";

const { t } = useI18n();
const emit = defineEmits(["reservation-cancelled"]);

const props = defineProps({
  reservation: Object,
  index: Number,
  isLoading: Boolean,
});

const toast = useToast();
const confirm = useConfirm();

const statusSeverity = computed(() => {
  return props.reservation.status === "submitted"
    ? "info"
    : props.reservation.status === "accepted"
    ? "success"
    : "danger";
});

const statusText = computed(() => {
  return t(`reservations.${props.reservation.status}`).toLocaleUpperCase();
});

const formatDate = (dateString) => {
  const date = new Date(dateString);
  return date.toLocaleDateString("lv-LV");
};

const formatTimeRange = (startTimeString, endTimeString) => {
  const startTime = moment(startTimeString).format("HH.mm");
  const endTime = moment(endTimeString).format("HH.mm");

  return `${startTime} - ${endTime}`;
};

const isEligibleForPDF = (reservation) => {
  return (
    reservation.status === "accepted" &&
    new Date(reservation.instructor_availability.end_time) <= new Date()
  );
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

const confirmCancel = (event, reservationID) => {
  confirm.require({
    target: event.currentTarget,
    message: t("confirm_cancel_message"),
    icon: "pi pi-exclamation-triangle",
    accept: async () => {
      await axios
        .delete(`/api/personal_reservations/${reservationID}`)
        .then(() => {
          toast.add({
            severity: "success",
            summary: t("cancel_success_summary"),
            detail: t("cancel_success_detail"),
            life: 3000,
          });
          emit("reservation-cancelled");
        })
        .catch((error) => {
          console.error(error);
        });
    },
  });
};
</script>

<style scoped>
/* Your styles here */
</style>
