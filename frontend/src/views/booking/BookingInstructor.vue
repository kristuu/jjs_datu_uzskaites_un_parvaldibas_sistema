<template>
  <Transition mode="out-in" name="fade">
    <template v-if="fetchDataStore.show">
      <div>
        <div
            class="flex flex-column lg:flex-row align-items-baseline text-primary lg:text-white mb-3"
        >
          <h1 class="fw-bold mb-0">Trenera profils</h1>
          <!--          <span class="ml-2"
                      ><i class="bi bi-caret-right-fill" />{{
                        `${fetchDataStore.instance.user.name} ${fetchDataStore.instance.user.surname}`
                      }}</span
                    >-->
        </div>
        <div class="grid mb-2">
          <div class="col-12">
            <div
                class="p-5 bg-primary lg:bg-white text-white lg:text-primary rounded shadow text-center"
            >
              <p class="fw-bold">
                Pārliecinies par savas kontaktinformācijas aktualitāti, lai
                nepieciešamības gadījumā varam ar tevi sazināties!
              </p>
            </div>
          </div>
        </div>
        <div class="grid">
          <div class="col-12 md:col-5 xl:col-4">
            <div
                class="bg-primary rounded shadow p-4 rounded flex flex-column h-full"
            >
              <div>
                <div class="grid">
                  <div class="col-6">
                    <div>
                      <img
                          :src="instance.user.profile_picture"
                          class="w-full"
                          style="border-radius: 0.375rem 0.375rem 0 0"
                      />
                    </div>
                    <div v-if="fetchDataStore.isFetching">
                      <Skeleton
                          height="2rem"
                          style="border-radius: 0 0 6px 6px"
                      />
                    </div>
                    <div v-else>
                      <div
                          class="surface-100 p-1 w-full"
                          style="border-radius: 0 0 0.375rem 0.375rem"
                      >
                        <div
                            class="surface-0 flex align-items-center gap-1 justify-content-center py-1 px-3"
                            style="
                            border-radius: 0 0 0.375rem 0.375rem;
                            box-shadow: 0px 1px 2px 0px rgba(0, 0, 0, 0.04),
                              0px 1px 2px 0px rgba(0, 0, 0, 0.06);
                          "
                        >
                          <template v-for="star in 5">
                            <i
                                v-if="star <= instance.rating"
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
                  <div class="col-6">
                    <div v-if="fetchDataStore.isFetching">
                      <Skeleton class="mb-2" height="1.5rem" width="75%"/>
                      <Skeleton class="mb-5" height="1.5rem"/>
                      <Skeleton style="height: 1rem"/>
                    </div>
                    <div v-else>
                      <h2 class="h2 font-bold p-0 m-0 mb-3">
                        {{ `${instance.user?.name} ${instance.user?.surname}` }}
                      </h2>
                      <span class="font-medium text-sm">{{
                          instance.certificate?.category?.name
                        }}</span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="pt-4 mb-5">
                <div v-if="fetchDataStore.isFetching">
                  <Skeleton class="mb-2" style="height: 1rem" width="90%"/>
                  <Skeleton class="mb-2" style="height: 1rem"/>
                  <Skeleton class="mb-2" style="height: 1rem" width="50%"/>
                  <Skeleton style="height: 2rem; width: 130px"/>
                </div>
                <div v-else>
                  <div class="mb-2">{{ instance.short_description }}</div>
                  <Button
                      class="text-primary"
                      label="RĀDĪT VAIRĀK"
                      raised
                      severity="secondary"
                      size="small"
                      @click="descriptionVisible = true"
                  />
                </div>
              </div>
              <div class="text-right bottom-0">
                <span class="fw-bold">Novērtē treneri:</span>
                <Rating
                    v-model="instance.rating"
                    :cancel="false"
                    class="justify-content-end mt-1"
                ></Rating>
              </div>
            </div>
          </div>
          <div class="col-12 md:col-7 xl:col-8">
            <div class="grid">
              <div class="col-12">
                <div class="bg-primary rounded shadow h-full text-center">
                  <h1 class="m-0 py-3">DATUMA IZVĒLE</h1>
                </div>
              </div>
              <div class="col-12">
                <div
                    class="bg-white rounded shadow h-full py-3 text-center d-flex flex-column justify-content-center"
                >
                  <Calendar
                      v-model="chosenDate"
                      :event-dates="eventDates"
                      :min-date="new Date()"
                      class="mx-auto"
                      inline
                      @dateSelect="onDateSelect"
                  >
                    <template #date="slotProps">
                      <div
                          :class="{ 'p-disabled': !isEvent(slotProps.date) }"
                          class="d-flex justify-content-center"
                      >
                        <span :class="isEvent(slotProps.date) ? `mb-2` : ``">{{
                            slotProps.date.day
                          }}</span>
                        <span
                            v-if="isEvent(slotProps.date)"
                            :style="{
                            backgroundColor: getBackgroundColor(slotProps.date),
                          }"
                            class="event-dot"
                        ></span>
                      </div>
                    </template>
                  </Calendar>
                  <Divider class="w-75 mx-auto mt-0"/>
                  <div class="text-end text-sm w-75 m-auto">
                    <p class="m-0 mb-1">
                      <span class="p-disabled">n</span>
                      <span> - nepieejams datums</span>
                    </p>
                    <p class="m-0 mb-1 d-flex align-items-center justify-content-end">
                      <div class="w-fit text-center">
                        <span>n</span>
                        <span
                            class="event-dot"
                            style="background-color: yellow; position: unset"
                        ></span>
                      </div>
                      <span class="ms-2">- 3 vai mazāk pieejamu laiku</span>
                    </p>
                    <p class="m-0 mb-2 d-flex align-items-center justify-content-end">
                      <div class="w-fit text-center">
                        <span>n</span>
                        <span
                            class="event-dot"
                            style="background-color: limegreen; position: unset"
                        ></span>
                      </div>
                      <span class="ms-2">- vairāk par 3 pieejamiem laikiem</span>
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </template>
  </Transition>

  <Dialog
      v-model:visible="descriptionVisible"
      :pt="{
      root: 'border-none',
      mask: {
        style: 'backdrop-filter: blur(4px)',
      },
    }"
      dismissableMask
      modal
      position="top"
      style="background: none"
  >
    <template #container="{ closeCallback }">
      <div class="grid mt-2">
        <div class="col-12">
          <div class="bg-primary rounded text-center shadow">
            <h1 class="py-4 m-0">TRENERA APRAKSTS</h1>
          </div>
        </div>
        <div class="col-12">
          <div class="bg-white rounded p-3">
            <div class="d-flex justify-content-between">
              <div>
                <div class="fw-bold">Izvēlētais treneris</div>
                <div>
                  {{ `${instance.user?.name} ${instance.user?.surname}` }}
                </div>
              </div>
              <Button
                  class="fs-2 p-0"
                  icon="bi bi-x-lg"
                  text
                  @click="closeCallback"
              />
            </div>
            <Divider/>
            <div style="max-height: 50vh; overflow: hidden auto;">
              {{ instance.description }}
            </div>
          </div>
        </div>
      </div>
    </template>
  </Dialog>

  <Dialog
      v-model:visible="bookingVisible"
      :pt="{ root: 'border-none', mask: { style: 'backdrop-filter: blur(4px)' } }"
      class="container-sm"
      dismissableMask
      modal
      position="top" style="background: none"
  >
    <template #container="{ closeCallback }">
      <div class="grid mt-2">
        <div class="col-12">
          <div class="bg-primary rounded text-center shadow">
            <h1 class="py-4 m-0">REZERVĀCIJAS PIETEIKŠANA</h1>
          </div>
        </div>
        <div class="col-12">
          <div class="bg-white rounded p-3">
            <div class="d-flex justify-content-between">
              <div>
                <div class="fw-bold">Izvēlētais treneris</div>
                <div>
                  {{ `${instance.user?.name} ${instance.user?.surname} - ${instance.certificate.category.name}` }}
                </div>
              </div>
              <div class="text-end">
                <div class="fw-bold">Izvēlētais datums</div>
                <div>{{ chosenDateDisplay }}</div>
              </div>
            </div>
            <Divider/>
            <div style="max-height: 50vh; overflow: hidden auto">
              <div class="fw-bold">
                Izvēlies vienu no pieejamajiem sākuma laikiem:
              </div>
              <div v-if="isFetchingTimes" class="grid mt-2">
                <div v-for="i in 8" class="col-12 md:col-6">
                  <Skeleton height="2rem"/>
                </div>
              </div>
              <div v-else class="grid mt-2">
                <div
                    v-if="sortedAvailableTimes.length === 0"
                    class="col-12 text-center"
                >
                  <span
                  >Diemžēl šajā datumā treneris pieteikumus nepieņem.</span
                  >
                </div>
                <div
                    v-for="time in sortedAvailableTimes"
                    :key="time.id"
                    class="col-12 md:col-6"
                >
                  <Button
                      :class="{
    'bg-primary text-white': time.selected && !time.reserved,
    'bg-white text-primary': !time.selected && !time.reserved
  }"
                      :disabled="time.reserved"
                      :label="`${time.label} (ilgums ${time.duration})`"
                      :severity="time.reserved ? 'secondary' : ''"
                      class="w-full"
                      @click="selectTime(time)"
                  ></Button>
                </div>
              </div>
            </div>
            <div class="text-end">
              <Button
                  :label="
        sortedAvailableTimes.length > 0 ? `PIETEIKT REZERVĀCIJU` : `AIZVĒRT`
      "
                  class="mt-4"
                  @click="submitReservation"
              />
            </div>
          </div>
        </div>
      </div>
    </template>
  </Dialog>
</template>

<script setup>
import {computed, onBeforeMount, ref} from "vue";
import axios from "@/services/axios"; // Import custom axios instance
import {useFetchDataStore} from "@/stores/fetchDataStore";
import {useRoute} from "vue-router";
import {useAuthStore} from "@/stores/authStore";
import moment from "moment";

const fetchDataStore = useFetchDataStore();
const authStore = useAuthStore();
const instance = computed(() => fetchDataStore.instance);
const route = useRoute();

let descriptionVisible = ref(false);
let bookingVisible = ref(false);
let chosenDate = ref(null);
let chosenDateDisplay = ref(null);

const availableTimes = ref([]);
let selectedTime = ref(null);

const disabledDates = ref([]);
const eventDates = ref([]);

const selectTime = (time) => {
  availableTimes.value.forEach((t) => (t.selected = false));
  time.selected = true;
  selectedTime.value = time;
};

const continueReservation = () => {
  // Handle continue reservation logic
  closeBookingModal();
};

const closeBookingModal = () => {
  bookingVisible.value = false;
  availableTimes.value = [];
};

const formatDateForDisplay = (date) => {
  const day = String(date.getDate()).padStart(2, "0");
  const month = String(date.getMonth() + 1).padStart(2, "0");
  const year = date.getFullYear();
  return `${day}.${month}.${year}`;
};

const formatDateForApi = (date) => {
  const day = String(date.getDate()).padStart(2, "0");
  const month = String(date.getMonth() + 1).padStart(2, "0");
  const year = date.getFullYear();
  return `${day}-${month}-${year}`;
};

const onDateSelect = async (date) => {
  availableTimes.value = [];
  chosenDateDisplay.value = formatDateForDisplay(date);
  chosenDate.value = formatDateForApi(date);
  bookingVisible.value = true;

  // Fetch data from backend for the selected date
  await fetchReservationsForDate(chosenDate.value);
};

const formatDuration = (start, end) => {
  const startDate = new Date(start);
  const endDate = new Date(end);
  const diffMs = endDate - startDate;
  const diffMins = Math.floor(diffMs / 60000);
  const hours = Math.floor(diffMins / 60);
  const minutes = diffMins % 60;
  let duration = "";
  if (hours > 0) {
    duration += `${hours}h`;
  }
  if (minutes > 0) {
    duration += `${hours > 0 ? " " : ""}${minutes}min`;
  }
  return duration;
};

let isFetchingTimes = ref(false);

const fetchReservationsForDate = async (date) => {
  try {
    isFetchingTimes.value = true;
    const response = await axios.get(
        `/api/availabilities/${instance.value.id}/${date}`
    );
    availableTimes.value = response.data.map((availability) => ({
      id: availability.id,
      label: moment(availability.start_time).format("HH.mm"),
      duration: formatDuration(availability.start_time, availability.end_time),
      selected: false,
      reserved: availability.reserved,
      startDate: availability.start_time // Make sure startDate is included
    }));
  } catch (error) {
    console.error("Error fetching availabilities:", error);
  } finally {
    isFetchingTimes.value = false;
  }
};

const fetchAvailabilityCounts = async () => {
  try {
    const response = await axios.get(
        `/api/availability-counts/${instance.value.id}`
    );
    const counts = response.data;
    disabledDates.value = counts
        .filter((count) => count.count === 0)
        .map((count) => new Date(count.date));
    eventDates.value = counts
        .filter((count) => count.count > 0)
        .map((count) => ({
          date: new Date(count.date),
          count: count.count,
        }));
  } catch (error) {
    console.error("Error fetching availability counts:", error);
  }
};

const sortedAvailableTimes = computed(() => {
  return availableTimes.value.slice().sort((a, b) => {
    const aTime = new Date(a.startDate).getTime();
    const bTime = new Date(b.startDate).getTime();
    return aTime - bTime;
  });
});

const isSameDay = (date1, date2) => {
  if (!date1 || !date2) return false;
  return (
      date1.getDate() === date2.getDate() &&
      date1.getMonth() === date2.getMonth() &&
      date1.getFullYear() === date2.getFullYear()
  );
};

const isEvent = (date) => {
  return (
      date &&
      eventDates.value.some((e) => {
        const eventDate = e.date;
        eventDate.setHours(0, 0, 0);
        const calendarDate = new Date(date.year, date.month, date.day);
        calendarDate.setHours(0, 0, 0);

        return eventDate.getTime() === calendarDate.getTime();
      })
  );
};

const getBackgroundColor = (date) => {
  const events = eventDates.value.find((e) => {
    const eventDate = new Date(e.date);
    const calendarDate = new Date(date.year, date.month, date.day);
    return eventDate.getTime() === calendarDate.getTime() && moment(eventDate) >= moment.now();
  });
  return events ? (events.count > 3 ? "limegreen" : "yellow") : "";
};

const submitReservation = async () => {
  try {
    const response = await axios.post('/api/reservations', {
      instructor_availability_id: selectedTime.value.id
    });

    fetchAvailabilityCounts();
  } catch (error) {
    console.error("Error submitting reservation:", error);
  } finally {
    bookingVisible.value = false;
  }
};

onBeforeMount(async () => {
  await fetchDataStore.fetchInstance("instructors", route.params.id);
  fetchAvailabilityCounts();
  fetchDataStore.showComponents();
});
</script>

<style scoped>
::v-deep .p-datepicker {
  border: none;
}

::v-deep .p-rating .p-rating-item .p-rating-icon {
  height: 30px;
  width: 30px;
}

::v-deep .p-rating .p-rating-item .p-rating-icon {
  color: #fff;
}

::v-deep
.p-rating:not(.p-disabled):not(.p-readonly)
.p-rating-item:hover
.p-rating-icon {
  color: gold;
}

.p-calendar {
  width: 100%;
}

@media only screen and (min-width: 768px) {
  .p-calendar {
    width: 75%;
  }
}

p {
  margin: 0;
}

th,
h5 {
  font-weight: bold;
}

nav ul {
  margin-bottom: 0 !important;
}

.custom-day {
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 100%;
  height: 100%;
}

.disabled-day {
  color: #ccc;
  pointer-events: none;
}

.event-dot {
  width: 15px;
  height: 6px;
  border-radius: 15px;
  display: block;
  position: absolute;
  bottom: 2px;
}
</style>
