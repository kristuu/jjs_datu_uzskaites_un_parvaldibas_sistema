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
          >{{ t("print_pdf") }}</Button
        >
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from "vue";
import { useToast } from "primevue/usetoast";
import { useConfirm } from "primevue/useconfirm";
import ConfirmPopup from "primevue/confirmpopup";
import html2pdf from "html2pdf.js";
import Skeleton from "primevue/skeleton";
import Tag from "primevue/tag";
import Button from "primevue/button";
import { useI18n } from "vue-i18n";
import axios from "@/services/axios";

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
  const startTime = new Date(startTimeString);
  const endTime = new Date(endTimeString);
  const padZero = (num) => String(num).padStart(2, "0");

  const startHours = padZero(startTime.getHours());
  const startMinutes = padZero(startTime.getMinutes());
  const endHours = padZero(endTime.getHours());
  const endMinutes = padZero(endTime.getMinutes());

  return `${startHours}.${startMinutes} - ${endHours}.${endMinutes}`;
};

const isEligibleForPDF = (reservation) => {
  return (
    reservation.status === "accepted" &&
    new Date(reservation.instructor_availability.end_time) <= new Date()
  );
};

const generatePDF = (reservation) => {
  const logo = "https://imgur.com/Z21sETW.png";

  const tempStart = new Date(reservation.instructor_availability.start_time);
  const tempEnd = new Date(reservation.instructor_availability.end_time);
  const tempStartDate = tempStart.getDate().toString().padStart(2, "0");
  const tempStartMonth = (tempStart.getMonth() + 1).toString().padStart(2, "0");
  const tempStartYear = tempStart.getFullYear().toString();
  const tempStartTime = tempStart.getTime().toString();
  const tempEndDate = tempEnd.getDate().toString().padStart(2, "0");
  const tempEndMonth = (tempEnd.getMonth() + 1).toString().padStart(2, "0");
  const tempEndYear = tempEnd.getFullYear().toString();
  const tempEndTime = tempEnd.getTime().toString();
  const element = document.createElement("div");
  element.innerHTML = `
    <div style="font-family: Arial, sans-serif; margin: 20px;">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <img src="${logo}" alt="logo" style="width: 150px;">
        <div style="text-align: right;">
          <p>Datums: ${formatDate(new Date())}</p>
          <p>Saistības izpildīt līdz: ${formatDate(
            new Date(Date.now() + 14 * 24 * 60 * 60 * 1000)
          )}</p>
        </div>
      </div>
      <div style="margin-bottom: 20px;">
        <h2 style="margin: 0;">Uzņēmuma rekvizīti:</h2>
        <p>Nosaukums: "Jauno jātnieku skola"</p>
        <p>Tiesiskā forma: Biedrība (BDR)</p>
        <p>Reģistrācijas numurs, datums: 40008114442, 20.04.2007</p>
        <p>Reģistrs, Ierakstīts reģistrā: Biedrību un nodibinājumu reģistrs, 20.04.2007</p>
        <p>SEPA identifikators: LV42ZZZ40008114442</p>
        <p>Dati no PVN maksātāju reģistra:</p>
        <p>PVN maksātāja numurs: LV40008114442</p>
        <p>Statuss: Ir aktīvs, 10.04.2014</p>
        <p>Juridiskā adrese: Rīga, Mārupes iela 4, LV-1002</p>
        <p>Pasta adrese: Mārupes iela 4, Rīga, LV-1002</p>
      </div>
      <h2 style="text-align: center; margin-bottom: 20px;">Pavadzīme Nr. ${
        reservation.id
      }</h2>
      <div style="margin-bottom: 20px;">
        <h3 style="margin: 0;">Saņēmējs:</h3>
        <p>${reservation.instructor.user.name} ${
    reservation.instructor.user.surname
  }</p>
        <p>${reservation.instructor.user.email}</p>
        <p>${reservation.instructor.user.phone}</p>
      </div>
      <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
        <thead>
          <tr>
            <th style="border-bottom: 1px solid #ddd; padding: 8px;">Nosaukums</th>
            <th style="border-bottom: 1px solid #ddd; padding: 8px;">Daudzums</th>
            <th style="border-bottom: 1px solid #ddd; padding: 8px;">Norises datums</th>
            <th style="border-bottom: 1px solid #ddd; padding: 8px;">Sākuma - Beigu laiks</th>
          </tr>
        </thead>
        <tbody>
          <tr class="text-center">
            <td style="border-bottom: 1px solid #ddd; padding: 8px;">Jāšanas treniņš</td>
            <td style="border-bottom: 1px solid #ddd; padding: 8px;">1</td>
            <td style="border-bottom: 1px solid #ddd; padding: 8px;">${tempStartDate}.${tempStartMonth}.${tempStartYear}</td>
            <td style="border-bottom: 1px solid #ddd; padding: 8px;">${formatTimeRange(
              reservation.instructor_availability.start_time,
              reservation.instructor_availability.end_time
            )}</td>
          </tr>
        </tbody>
      </table>
      <div style="margin-top: 20px;">
        <p><strong>Šis NAV rēķins! Šī pavadzīme kopā ar pakalpojuma apmaksu apliecinošu dokumentu - čeku - ir uzskatāma par pakalpojuma sniegšanas-saņemšanas pierādījumu.</strong></p>
        <p style="font-style: italic; color: #888; text-align: center;">Rēķins sagatavots elektroniski un derīgs bez paraksta.</p>
      </div>
    </div>
  `;
  html2pdf()
    .from(element)
    .set({
      margin: 0.5,
      filename: `JJS-rezervacija-${
        reservation.instructor.certificate.category.name
      }-${reservation.instructor.user.name}_${
        reservation.instructor.user.surname
      }-${tempStartDate.padStart(2, "0")}${tempStartMonth.padStart(2, "0")}-${
        reservation.id
      }.pdf`,
      html2canvas: { scale: 2 },
      jsPDF: { unit: "in", format: "a4", orientation: "portrait" },
    })
    .save();
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

<style scoped></style>
