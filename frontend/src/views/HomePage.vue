<template>
  <Transition mode="out-in" name="fade">
    <div v-if="useFetchDataStore().show" class="container-xl mt-3 lg:mt-0">
      <div>
        <div class="grid">
          <div class="col-12 lg:col-3">
            <div class="grid">
              <div class="col-12">
                <div
                  class="p-2 bg-primary lg:bg-white rounded shadow text-center h-full"
                >
                  <h1 class="m-0 text-white lg:text-primary">
                    {{ $t("home").toLocaleUpperCase() }}
                  </h1>
                </div>
              </div>
              <div class="col-12">
                <div class="grid">
                  <UserInfo />
                </div>
              </div>
            </div>
          </div>
          <div class="col-12 lg:col-9">
            <div class="grid">
              <div class="col-12">
                <UserReservationList
                  :reservations="usersReservations"
                  @showAllReservations="showAllUserReservations"
                  @update:reservations="usersReservations = $event"
                  @close-all-reservations-open-cancel="
                    (reservation) => {
                      chosenCancelReservation = reservation;
                      chosenCancelReservation.reason = '';
                      cancelConfirmationVisible = true;
                    }
                  "
                />
              </div>
              <div
                v-if="
                  is('dressage instructor') || is('show jumping instructor')
                "
                class="col-12"
              >
                <InstructorReservationList
                  :reservations="instructorReservations"
                  @showAllInstructorsReservations="
                    showAllInstructorReservations
                  "
                  @update:reservations="instructorReservations = $event"
                  @close-all-reservations-open-cancel="
                    (reservation) => {
                      chosenCancelReservation = reservation;
                      chosenCancelReservation.reason = '';
                      cancelConfirmationVisible = true;
                    }
                  "
                />
              </div>
              <div class="col-12 mt-3">
                <div class="grid">
                  <div class="col-12 xl:col-6">
                    <div class="p-3 bg-white rounded shadow h-full">
                      <div
                        class="flex justify-content-between align-items-center"
                      >
                        <h2 class="m-0 text-primary">
                          {{ $t(`competitions`).toLocaleUpperCase() }}
                        </h2>
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
                  <div class="col-12 xl:col-6">
                    <div class="p-3 bg-white rounded shadow h-full">
                      <div
                        class="flex justify-content-between align-items-center"
                      >
                        <h2 class="m-0 text-primary">
                          {{ $t(`seminars`).toLocaleUpperCase() }}
                        </h2>
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
                  <div class="col-12">
                    <div
                      class="bg-white rounded shadow p-3 fw-bold text-center text-primary"
                    >
                      <p class="m-0">
                        Lai skatītu aktuālāko pilna sacensību saraksta versiju,
                        <a
                          class="text-decoration-underline"
                          href="https://www.leflatvia.lv/web/?id=400053"
                          rel="noopener noreferrer"
                          target="_blank"
                          >spied šeit</a
                        >
                      </p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-12 lg:col-9"></div>
        </div>
      </div>
    </div>
  </Transition>

  <AllReservationsDialog
    :isLoading="isFetchingAllReservations"
    :reservations="allReservations"
    :visible="allReservationsVisible"
    @update:visible="allReservationsVisible = $event"
    @close-all-reservations-open-cancel="
      (reservation) => {
        allReservationsVisible = false;
        chosenCancelReservation = reservation;
        chosenCancelReservation.reason = '';
        cancelConfirmationVisible = true;
      }
    "
  />

  <AllInstructorsReservationsDialog
    :isLoading="isFetchingAllReservations"
    :reservations="allReservations"
    :visible="allInstructorsReservationsVisible"
    @update:visible="allInstructorsReservationsVisible = $event"
    @close-all-reservations-open-cancel="
      (reservation) => {
        allInstructorsReservationsVisible = false;
        chosenCancelReservation = reservation;
        chosenCancelReservation.reason = '';
        cancelConfirmationVisible = true;
      }
    "
  />

  <Dialog
    v-model:visible="cancelConfirmationVisible"
    :pt="{ root: 'border-none', mask: { style: 'backdrop-filter: blur(4px)' } }"
    class="container-sm"
    dismissableMask
    max-length="255"
    modal
    position="top"
    style="background: none"
  >
    <template #container="{ closeCallback }">
      <div class="grid mt-2">
        <div class="col-12">
          <div class="bg-primary rounded text-center shadow">
            <h1 class="py-4 m-0">
              REZERVĀCIJAS PIETEIKUMA
              {{
                chosenCancelReservation.status === "denied"
                  ? "NORAIDĪŠANA"
                  : "ATCELŠANA"
              }}
            </h1>
          </div>
        </div>
        <div class="col-12">
          <div class="bg-white rounded p-3">
            <div class="d-flex justify-content-between">
              <div>
                <div class="fw-bold">Treneris</div>
                <div>
                  {{
                    `${chosenCancelReservation.instructor?.user.name} ${chosenCancelReservation.instructor?.user.surname} - ${chosenCancelReservation.instructor?.certificate.category.name}`
                  }}
                </div>
                <div
                  v-if="chosenCancelReservation.status === 'denied'"
                  class="mt-2"
                >
                  <div class="fw-bold">Klients</div>
                  <div>
                    {{
                      `${chosenCancelReservation.user?.name} ${chosenCancelReservation.user?.surname}`
                    }}
                  </div>
                </div>
              </div>
              <div class="text-end">
                <div class="fw-bold">Rezervācijas laiks</div>
                <div>
                  {{
                    formatDate(
                      chosenCancelReservation.instructor_availability
                        ?.start_time
                    )
                  }}
                </div>
                <div>
                  {{
                    formatTimeRange(
                      chosenCancelReservation.instructor_availability
                        .start_time,
                      chosenCancelReservation.instructor_availability.end_time
                    )
                  }}
                </div>
              </div>
            </div>
            <Divider />
            <div style="max-height: 50vh; overflow: hidden auto">
              <div class="mb-3">
                <p class="m-0 mb-2">
                  Lai
                  {{
                    chosenCancelReservation.status === "denied"
                      ? "noraidītu"
                      : "atceltu"
                  }}
                  izvēlēto rezervācijas pieteikumu, nepieciešams ievadīt
                  iemeslu:
                </p>
                <div class="relative">
                  <Textarea
                    v-model="chosenCancelReservation.reason"
                    autoResize
                    class="w-full pb-4"
                    maxlength="255"
                  />
                  <span
                    class="text-sm absolute right-0 bottom-0 p-2 text-secondary"
                    >{{ cancelReservationCharactersRemaining }}</span
                  >
                </div>
                <InputError :errors="errorList.reason" />
              </div>
            </div>
            <div class="text-end">
              <Button
                :label="
                  `Apstiprināt ${
                    chosenCancelReservation.status === 'denied'
                      ? 'noraidīšanu'
                      : 'atcelšanu'
                  }`.toLocaleUpperCase()
                "
                class="mt-4"
                @click="
                  chosenCancelReservation.status === 'denied'
                    ? submitReservationDenial()
                    : submitReservationCancellation()
                "
              />
            </div>
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
import UserInfo from "@/components/UserInfo.vue";
import AllReservationsDialog from "@/components/AllReservationsDialog.vue";
import { useAuthStore } from "@/stores/authStore";
import Button from "primevue/button";
import moment from "moment/moment";
import { useErrorStore } from "@/stores/errorStore";
import InputError from "@/components/error/inputError.vue";

const fetchDataStore = useFetchDataStore();
const authStore = useAuthStore();
const errorStore = useErrorStore();

let errorList = computed(() => errorStore.errorList);

let time = ref(new Date().toLocaleTimeString());
let timer = null;

let date = ref(new Date().toLocaleDateString());

const seminarsPercentage = ref(0);
const competitionsPercentage = ref(0);

const competitions = ref([]);
const seminars = ref([]);

const usersReservations = ref([]);
const instructorReservations = ref([]);

const allReservationsVisible = ref(false);
const allInstructorsReservationsVisible = ref(false);
const allReservations = ref([]);
const isFetchingAllReservations = ref(false);

let cancelConfirmationVisible = ref(false);
let chosenCancelReservation = ref({ reason: "", status: "cancelled" });
let cancelReservationCharactersRemaining = computed(() => {
  return `${255 - chosenCancelReservation.value.reason.length}/255`;
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

const showAllUserReservations = async () => {
  try {
    isFetchingAllReservations.value = true;
    allReservationsVisible.value = true;
    const response = await axios.get("/api/getAllUserReservations");
    allReservations.value = response.data;
  } catch (error) {
    console.error("Error fetching all reservations:", error);
  } finally {
    isFetchingAllReservations.value = false;
  }
};

const showAllInstructorReservations = async () => {
  try {
    isFetchingAllReservations.value = true;
    allInstructorsReservationsVisible.value = true;
    const response = await axios.get("/api/getAllInstructorReservations");
    allReservations.value = response.data;
  } catch (error) {
    console.error("Error fetching all reservations:", error);
  } finally {
    isFetchingAllReservations.value = false;
  }
};

onBeforeMount(async () => {
  fetchDataStore.setIsFetching(true);
  await axios
    .get("/api/upcoming_events")
    .then((response) => {
      competitions.value = response.data.competitions;
      seminars.value = response.data.seminars;
    })
    .catch((error) => {
      console.error(error);
    })
    .finally(() => {
      fetchDataStore.setIsFetching(false);
    });

  await axios
    .get(`/api/event_percentage`)
    .then((response) => {
      seminarsPercentage.value = response.data.seminars;
      competitionsPercentage.value = response.data.competitions;
    })
    .catch((error) => {
      console.error(error);
    })
    .finally(() => {
      fetchDataStore.showComponents();
    });

  await fetchUserReservationData();
  await fetchInstructorReservationData();

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
});

const fetchUserReservationData = async () => {
  usersReservations.value = [];
  fetchDataStore.setIsFetching(true);
  await axios
    .get(`/api/homeData`)
    .then((response) => {
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

const fetchInstructorReservationData = async () => {
  instructorReservations.value = [];
  fetchDataStore.setIsFetching(true);
  await axios
    .get(`api/instructorReservations`)
    .then((response) => {
      instructorReservations.value = response.data;
    })
    .catch((error) => {
      console.error(error);
    })
    .finally(() => {
      fetchDataStore.showComponents();
      fetchDataStore.setIsFetching(false);
    });
};

const submitReservationCancellation = async () => {
  try {
    const response = await axios.patch(
      `/api/cancel_reservation/${chosenCancelReservation.value.id}`,
      {
        reason: chosenCancelReservation.value.reason,
      }
    );
    console.log("Reservation cancelled:", response);
    cancelConfirmationVisible.value = false;
  } catch (error) {
    console.log(error.response);
    console.log(chosenCancelReservation.value.reason);
    if (error.response.status === 422) {
      errorStore.setErrorList(error.response.data);
    } else {
      console.error(error);
    }
  }
};

const submitReservationDenial = async () => {
  try {
    const response = await axios.patch(
      `/api/deny_reservation/${chosenCancelReservation.value.id}`,
      {
        reason: chosenCancelReservation.value.reason,
      }
    );
    console.log("Reservation denied:", response);
    cancelConfirmationVisible.value = false;
  } catch (error) {
    console.error(error.response);
    if (error.response.status === 422) {
      errorStore.setErrorList(error.response.data);
    } else {
      console.error(error);
    }
  }
};

onMounted(() => {
  timer = setInterval(() => {
    time.value = new Date().toLocaleTimeString();
  }, 1000);

  console.log(new Date().toString());
});

onUnmounted(() => {
  clearInterval(timer);
});
</script>

<style scoped></style>
