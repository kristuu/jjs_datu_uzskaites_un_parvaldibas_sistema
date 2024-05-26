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
                        class="w-full"
                        src="https://via.placeholder.com/720x1080/eee?text=PROFILA%20FOTO"
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
                          <i
                            v-for="i in 3"
                            class="pi pi-star-fill text-primary"
                          ></i>
                          <i v-for="i in 2" class="pi pi-star text-primary"></i>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-6">
                    <div v-if="fetchDataStore.isFetching">
                      <Skeleton class="mb-2" height="1.5rem" width="75%" />
                      <Skeleton class="mb-5" height="1.5rem" />
                      <Skeleton style="height: 1rem" />
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
                  <Skeleton class="mb-2" style="height: 1rem" width="90%" />
                  <Skeleton class="mb-2" style="height: 1rem" />
                  <Skeleton class="mb-2" style="height: 1rem" width="50%" />
                  <Skeleton style="height: 2rem; width: 130px" />
                </div>
                <div v-else>
                  <div class="mb-2">{{ instance.description }}</div>
                  <Button
                    class="text-primary"
                    label="RĀDĪT VAIRĀK"
                    raised
                    severity="secondary"
                    size="small"
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
            <div
              class="bg-white rounded shadow h-full w-full text-center d-flex"
            >
              <Calendar
                :min-date="new Date()"
                class="m-auto md:w-full"
                inline
              />
            </div>
          </div>
        </div>
      </div>
    </template>
  </Transition>

  <Button icon="pi pi-user" label="Login" @click="visible = true" />

  <Dialog
    v-model:visible="visible"
    :pt="{
      root: 'border-none',
      mask: {
        style: 'backdrop-filter: blur(2px)',
      },
    }"
    modal
  >
    <template #container="{ closeCallback }">
      <div
        class="flex flex-column px-5 py-5 gap-4"
        style="
          border-radius: 12px;
          background-image: radial-gradient(
            circle at left top,
            var(--primary-400),
            var(--primary-700)
          );
        "
      >
        <svg
          class="block mx-auto"
          fill="none"
          height="40"
          viewBox="0 0 35 40"
          width="35"
          xmlns="http://www.w3.org/2000/svg"
        >
          <path
            d="M25.87 18.05L23.16 17.45L25.27 20.46V29.78L32.49 23.76V13.53L29.18 14.73L25.87 18.04V18.05ZM25.27 35.49L29.18 31.58V27.67L25.27 30.98V35.49ZM20.16 17.14H20.03H20.17H20.16ZM30.1 5.19L34.89 4.81L33.08 12.33L24.1 15.67L30.08 5.2L30.1 5.19ZM5.72 14.74L2.41 13.54V23.77L9.63 29.79V20.47L11.74 17.46L9.03 18.06L5.72 14.75V14.74ZM9.63 30.98L5.72 27.67V31.58L9.63 35.49V30.98ZM4.8 5.2L10.78 15.67L1.81 12.33L0 4.81L4.79 5.19L4.8 5.2ZM24.37 21.05V34.59L22.56 37.29L20.46 39.4H14.44L12.34 37.29L10.53 34.59V21.05L12.42 18.23L17.45 26.8L22.48 18.23L24.37 21.05ZM22.85 0L22.57 0.69L17.45 13.08L12.33 0.69L12.05 0H22.85Z"
            fill="var(--primary-700)"
          />
          <path
            d="M30.69 4.21L24.37 4.81L22.57 0.69L22.86 0H26.48L30.69 4.21ZM23.75 5.67L22.66 3.08L18.05 14.24V17.14H19.7H20.03H20.16H20.2L24.1 15.7L30.11 5.19L23.75 5.67ZM4.21002 4.21L10.53 4.81L12.33 0.69L12.05 0H8.43002L4.22002 4.21H4.21002ZM21.9 17.4L20.6 18.2H14.3L13 17.4L12.4 18.2L12.42 18.23L17.45 26.8L22.48 18.23L22.5 18.2L21.9 17.4ZM4.79002 5.19L10.8 15.7L14.7 17.14H14.74H15.2H16.85V14.24L12.24 3.09L11.15 5.68L4.79002 5.2V5.19Z"
            fill="var(--primary-200)"
          />
        </svg>
        <div class="inline-flex flex-column gap-2">
          <label class="text-primary-50 font-semibold" for="username"
            >Username</label
          >
          <InputText
            id="username"
            class="bg-white-alpha-20 border-none p-3 text-primary-50 w-20rem"
          ></InputText>
        </div>
        <div class="inline-flex flex-column gap-2">
          <label class="text-primary-50 font-semibold" for="password"
            >Password</label
          >
          <InputText
            id="password"
            class="bg-white-alpha-20 border-none p-3 text-primary-50 w-20rem"
            type="password"
          ></InputText>
        </div>
        <div class="flex align-items-center gap-3">
          <Button
            class="p-3 w-full text-primary-50 border-1 border-white-alpha-30 hover:bg-white-alpha-10"
            label="Cancel"
            text
            @click="closeCallback"
          ></Button>
          <Button
            class="p-3 w-full text-primary-50 border-1 border-white-alpha-30 hover:bg-white-alpha-10"
            label="Sign-In"
            text
            @click="closeCallback"
          ></Button>
        </div>
      </div>
    </template>
  </Dialog>
</template>

<script setup>
import { computed, onBeforeMount, onMounted, ref } from "vue";

import { useFetchDataStore } from "@/stores/fetchDataStore";
import { useRoute } from "vue-router";

const fetchDataStore = useFetchDataStore();

const instance = computed(() => fetchDataStore.instance);

const route = useRoute();

const onRowSelect = async (event) => {
  await fetchDataStore.fetchInstance("instructors", event.data.id);
  visible.value = true;
};

let visible = ref(false);

onBeforeMount(async () => {
  await fetchDataStore.fetchInstance("instructors", route.params.id);
});

onMounted(() => {
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
</style>
