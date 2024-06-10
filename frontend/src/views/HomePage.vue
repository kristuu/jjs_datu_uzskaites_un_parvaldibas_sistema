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
            <ReservationList
              :reservations="usersReservations"
              @showAllReservations="showAllReservations"
              @update:reservations="usersReservations = $event"
            />
          </div>
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
            <h1 class="py-4 m-0">REZERVĀCIJAS PIETEIKUMA ATCELŠANA</h1>
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
              <div>
                <p class="m-0 mb-2">
                  Lai atceltu izvēlēto rezervācijas pieteikumu, nepieciešams
                  ievadīt tā atcelšanas iemeslu:
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
              </div>
            </div>
            <div class="text-end">
              <Button :label="`Atcelt`.toLocaleUpperCase()" class="mt-4" />
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
import ReservationList from "@/components/ReservationList.vue";
import AllReservationsDialog from "@/components/AllReservationsDialog.vue";
import { useAuthStore } from "@/stores/authStore";
import Button from "primevue/button";
import moment from "moment/moment";

const fetchDataStore = useFetchDataStore();
const authStore = useAuthStore();

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
const isFetchingAllReservations = ref(false);

let cancelConfirmationVisible = ref(false);
let chosenCancelReservation = ref({ reason: "" });
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

  await fetchReservationData();
});

const fetchReservationData = async () => {
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

const submitReservationCancellation = async () => {};

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
