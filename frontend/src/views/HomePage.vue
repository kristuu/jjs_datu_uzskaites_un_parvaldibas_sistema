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
  />
</template>

<script setup>
import { onBeforeMount, onMounted, onUnmounted, ref } from "vue";
import { useFetchDataStore } from "@/stores/fetchDataStore";
import axios from "@/services/axios";
import UserInfo from "@/components/UserInfo.vue";
import ReservationList from "@/components/ReservationList.vue";
import AllReservationsDialog from "@/components/AllReservationsDialog.vue";
import { useAuthStore } from "@/stores/authStore";

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
