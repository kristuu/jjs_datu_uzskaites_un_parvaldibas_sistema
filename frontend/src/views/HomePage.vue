<template>
  <Transition mode="out-in" name="fade">
    <div v-if="useFetchDataStore().show" class="container-xl mt-3 lg:mt-0">
      <div>
        <div class="grid">
          <div class="col-12 lg:col-3">
            <div class="grid">
              <div class="col-12 h-full">
                <div class="grid">
                  <div class="col-12">
                    <div
                      class="p-2 bg-primary lg:bg-white rounded shadow text-center h-full"
                    >
                      <h1 class="m-0 text-white lg:text-primary">
                        {{ $t(`home`).toLocaleUpperCase() }}
                      </h1>
                    </div>
                  </div>
                  <div class="col-12">
                    <div
                      class="p-2 bg-primary lg:bg-white rounded shadow h-full"
                    >
                      <div>
                        <p class="m-0 fw-bold text-white lg:text-primary">
                          {{ $t(`authorized_as`).toLocaleUpperCase() }}
                        </p>
                        <p class="m-0 text-white lg:text-primary">
                          {{
                            `${authStore.user.name} ${authStore.user.surname}`
                          }}
                        </p>
                      </div>
                    </div>
                  </div>
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
                    <Button
                      class="mt-2 md:m-0"
                      icon="bi bi-plus-lg"
                      outlined
                      @click="$router.push({ name: 'BookingList' })"
                    />
                  </div>
                  <Divider class="my-3" />
                  <DataView :value="usersReservations">
                    <template #list="slotProps">
                      <div class="grid grid-nogutter">
                        <div
                          v-for="(item, index) in slotProps.items"
                          :key="index"
                          class="col-12"
                        >
                          <div
                            :class="{
                              'border-top-1 surface-border': index !== 0,
                            }"
                            class="flex flex-column sm:flex-row p-3 gap-3"
                          >
                            <div class="md:w-7rem relative">
                              <img
                                :src="item.instructor.user.profile_picture"
                                class="block xl:block mx-auto border-round w-full"
                                style="border-radius: 0.375rem 0.375rem 0 0"
                              />
                              <Tag
                                v-if="!fetchDataStore.isFetching"
                                :severity="
                                  item.status === 'submitted'
                                    ? 'info'
                                    : item.status === 'accepted'
                                    ? 'success'
                                    : 'danger'
                                "
                                :value="
                                  $t(
                                    `reservations.${item.status}`
                                  ).toLocaleUpperCase()
                                "
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
                                  <div
                                    v-if="fetchDataStore.isFetching"
                                    class="w-100"
                                  >
                                    <Skeleton height="1rem" width="100px" />
                                  </div>
                                  <span
                                    v-else
                                    class="font-medium text-secondary text-sm"
                                    >{{
                                      item.instructor.certificate.category.name
                                    }}</span
                                  >
                                  <div
                                    v-if="fetchDataStore.isFetching"
                                    class="w-100"
                                  >
                                    <Skeleton
                                      class="mt-2"
                                      height="2rem"
                                      width="100%"
                                    />
                                  </div>
                                  <div
                                    v-else
                                    class="text-lg font-medium text-900 mt-2"
                                  >
                                    {{
                                      `${item.instructor.user.name} ${item.instructor.user.surname}`
                                    }}
                                  </div>
                                </div>
                                <div>
                                  <div
                                    v-if="fetchDataStore.isFetching"
                                    class="w-100"
                                  >
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
                                        box-shadow: 0px 1px 2px 0px
                                            rgba(0, 0, 0, 0.04),
                                          0px 1px 2px 0px rgba(0, 0, 0, 0.06);
                                      "
                                    >
                                      <template v-for="star in 5">
                                        <i
                                          v-if="star <= item.instructor.rating"
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
                                <div
                                  v-if="fetchDataStore.isFetching"
                                  class="w-100"
                                >
                                  <Skeleton height="2rem" width="75%" />
                                  <Skeleton
                                    class="mt-2"
                                    height="2rem"
                                    width="100%"
                                  />
                                </div>
                                <div v-else>
                                  <div class="text-sm fw-bold text-black">
                                    Datums
                                    <br />
                                    <span
                                      class="text-xl font-semibold text-900"
                                    >
                                      {{
                                        formatDate(
                                          item.instructor_availability
                                            .start_time
                                        )
                                      }}
                                    </span>
                                  </div>
                                  <div class="mt-2 text-sm fw-bold text-black">
                                    Laiks
                                    <br />
                                    <span
                                      class="text-xl font-semibold text-900"
                                    >
                                      {{
                                        formatTimeRange(
                                          item.instructor_availability
                                            .start_time,
                                          item.instructor_availability.end_time
                                        )
                                      }}
                                    </span>
                                  </div>
                                </div>
                                <div
                                  v-if="
                                    !['accepted', 'denied'].includes(
                                      item.status
                                    )
                                  "
                                  class="flex flex-row-reverse md:flex-row gap-2"
                                >
                                  <ConfirmPopup></ConfirmPopup>
                                  <Button
                                    :label="
                                      $t(
                                        `reservations.cancel`
                                      ).toLocaleUpperCase()
                                    "
                                    class="flex-auto md:flex-initial white-space-nowrap"
                                    outlined
                                    size="small"
                                    text
                                    @click="confirm1($event, item.id)"
                                  ></Button>
                                </div>
                                <Button
                                  v-if="isEligibleForPDF(item)"
                                  @click="() => generatePDF(item)"
                                  >Drukāt PDF
                                </Button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </template>
                    <template v-if="fetchDataStore.isFetching" #empty>
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
                                  <Skeleton
                                    class="mt-2"
                                    height="2rem"
                                    width="100%"
                                  />
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
                                  <Skeleton
                                    class="mt-2"
                                    height="2rem"
                                    width="100%"
                                  />
                                </div>
                                <div
                                  class="flex flex-row-reverse md:flex-row gap-2"
                                >
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
                  <Divider class="m-0" />
                  <Button class="my-3 float-end" @click="showAllReservations">
                    VISAS REZERVĀCIJAS
                  </Button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </Transition>

  <Dialog
    v-model:visible="allReservationsVisible"
    :pt="{ root: 'border-none', mask: { style: 'backdrop-filter: blur(4px)' } }"
    class="container-lg"
    dismissableMask
    modal
    position="top"
    style="background: none"
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
            <Button
              class="fs-2 p-0 mb-2 float-end"
              icon="bi bi-x-lg"
              text
              @click="closeCallback"
            />
            <Divider class="my-3" />
            <DataView
              :value="allReservations"
              style="max-height: 65vh; overflow: hidden auto"
            >
              <template #list="slotProps">
                <div class="grid grid-nogutter">
                  <div
                    v-for="(item, index) in slotProps.items"
                    :key="index"
                    class="col-12"
                  >
                    <div
                      :class="{
                        'border-top-1 surface-border': index !== 0,
                      }"
                      class="flex flex-column sm:flex-row p-3 gap-3"
                    >
                      <div class="md:w-7rem relative">
                        <img
                          :src="item.instructor.user.profile_picture"
                          class="block xl:block mx-auto border-round w-full"
                          style="border-radius: 0.375rem 0.375rem 0 0"
                        />
                        <Tag
                          v-if="!isFetchingAllReservations"
                          :severity="
                            item.status === 'submitted'
                              ? 'info'
                              : item.status === 'accepted'
                              ? 'success'
                              : 'danger'
                          "
                          :value="
                            $t(
                              `reservations.${item.status}`
                            ).toLocaleUpperCase()
                          "
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
                            <div v-if="isFetchingAllReservations" class="w-100">
                              <Skeleton height="1rem" width="100px" />
                            </div>
                            <span
                              v-else
                              class="font-medium text-secondary text-sm"
                              >{{
                                item.instructor.certificate.category.name
                              }}</span
                            >
                            <div v-if="isFetchingAllReservations" class="w-100">
                              <Skeleton
                                class="mt-2"
                                height="2rem"
                                width="100%"
                              />
                            </div>
                            <div
                              v-else
                              class="text-lg font-medium text-900 mt-2"
                            >
                              {{
                                `${item.instructor.user.name} ${item.instructor.user.surname}`
                              }}
                            </div>
                          </div>
                          <div>
                            <div v-if="isFetchingAllReservations" class="w-100">
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
                                  box-shadow: 0px 1px 2px 0px
                                      rgba(0, 0, 0, 0.04),
                                    0px 1px 2px 0px rgba(0, 0, 0, 0.06);
                                "
                              >
                                <template v-for="star in 5">
                                  <i
                                    v-if="star <= item.instructor.rating"
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
                          <div v-if="isFetchingAllReservations" class="w-100">
                            <Skeleton height="2rem" width="75%" />
                            <Skeleton class="mt-2" height="2rem" width="100%" />
                          </div>
                          <div v-else>
                            <div class="text-sm fw-bold text-black">
                              Datums
                              <br />
                              <span class="text-xl font-semibold text-900">
                                {{
                                  formatDate(
                                    item.instructor_availability.start_time
                                  )
                                }}
                              </span>
                            </div>
                            <div class="mt-2 text-sm fw-bold text-black">
                              Laiks
                              <br />
                              <span class="text-xl font-semibold text-900">
                                {{
                                  formatTimeRange(
                                    item.instructor_availability.start_time,
                                    item.instructor_availability.end_time
                                  )
                                }}
                              </span>
                            </div>
                          </div>
                          <div
                            v-if="!['accepted', 'denied'].includes(item.status)"
                            class="flex flex-row-reverse md:flex-row gap-2"
                          >
                            <ConfirmPopup></ConfirmPopup>
                            <Button
                              :label="
                                $t(`reservations.cancel`).toLocaleUpperCase()
                              "
                              class="flex-auto md:flex-initial white-space-nowrap"
                              outlined
                              size="small"
                              text
                              @click="confirm1($event, item.id)"
                            ></Button>
                          </div>
                          <Button
                            v-if="item.status === 'accepted'"
                            :disabled="!isEligibleForPDF(item)"
                            size="small"
                            @click="generatePDF(item)"
                            >Drukāt PDF
                          </Button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </template>
              <template v-if="isFetchingAllReservations" #empty>
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
import { computed, onBeforeMount, onMounted, onUnmounted, ref } from "vue";
import { useFetchDataStore } from "@/stores/fetchDataStore";
import axios from "@/services/axios";
import { useToast } from "primevue/usetoast";
import { useConfirm } from "primevue/useconfirm";
import ConfirmPopup from "primevue/confirmpopup";
import { useAuthStore } from "@/stores/authStore";
import html2pdf from "html2pdf.js";

const authStore = useAuthStore();
const fetchDataStore = useFetchDataStore();

let time = ref(new Date().toLocaleTimeString());
let timer = null;

let date = ref(new Date().toLocaleDateString());

const seminarsPercentage = ref(0);
const competitionsPercentage = ref(0);

const competitions = ref([]);
const seminars = ref([]);
const usersReservations = ref([]);

const allReservationsVisible = ref(false);
const allReservations = ref([]);

const confirm = useConfirm();
const toast = useToast();

const reservationToCancel = ref();

const acceptedReservations = computed(() => {
  return usersReservations.value.filter(
    (reservation) =>
      reservation.status === "accepted" &&
      new Date(reservation.instructor_availability.end_time) <= new Date()
  );
});

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

const confirm1 = (event, reservationID) => {
  confirm.require({
    target: event.currentTarget,
    message: "Vai esi pārliecināts, ka vēlies atcelt?",
    icon: "pi pi-exclamation-triangle",
    rejectClass: "p-button-secondary p-button-outlined p-button-sm",
    acceptClass: "p-button-sm",
    rejectLabel: "Nē",
    acceptLabel: "Jā",
    accept: async () => {
      allReservationsVisible.value = false;
      await axios
        .delete(`/api/personal_reservations/${reservationID}`)
        .then(() => {
          toast.add({
            severity: "success",
            summary: "Pieteikuma dzēšana",
            detail: "Pieteikums atcelts veiksmīgi",
            life: 3000,
          });
        })
        .catch((error) => {
          console.error(error);
        })
        .finally(() => {
          fetchReservationData();
        });
    },
  });
};

const isFetchingAllReservations = ref(false);

const showAllReservations = async () => {
  try {
    isFetchingAllReservations.value = true;
    allReservationsVisible.value = true;
    const response = await axios.get("/api/getAllReservations");
    allReservations.value = response.data;
  } catch (error) {
    console.error("Error fetching all reservations:", error);
  } finally {
    isFetchingAllReservations.value = false;
  }
};

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

onBeforeMount(async () => {
  fetchDataStore.isFetching = true;
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
      fetchDataStore.isFetching = false;
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
      fetchDataStore.showComponents();
    });

  await fetchReservationData();
});

const fetchReservationData = async () => {
  usersReservations.value = [];
  fetchDataStore.setIsFetching(true);
  await axios
    .get(`/api/homeData`)
    .then((response) => {
      console.log(response);
      usersReservations.value = response.data;
    })
    .catch((error) => {
      console.error(error);
    })
    .finally(() => {
      fetchDataStore.showComponents();
      fetchDataStore.setIsFetching(false);
    });
};

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
