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
              <div class="flex">
                <div class="grid">
                  <div class="col-6">
                    <div>
                      <img
                        class="w-full"
                        src="https://via.placeholder.com/720x1080/eee?text=PROFILA%20FOTO"
                        style="border-radius: 0.375rem 0.375rem 0 0"
                      />
                    </div>
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
                  <div class="col-6">
                    <div>
                      <h2 class="h2 font-bold p-0 m-0 mb-3">
                        {{ `${instance.user.name} ${instance.user.surname}` }}
                      </h2>
                      <span class="font-medium text-sm">{{
                        instance.certificate.category.name
                      }}</span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="pt-4 mb-5">
                <div>
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
