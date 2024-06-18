<template>
  <Transition mode="out-in" name="fade">
    <template v-if="fetchDataStore.show">
      <div>
        <div
          class="flex flex-column lg:flex-row align-items-baseline text-primary lg:text-white mb-3"
        >
          <h1 class="fw-bold mb-0">Treniņa rezervācija</h1>
          <span class="ml-2"
            ><i class="bi bi-caret-right-fill" />Pašlaik pieejami
            {{ fetchDataStore.totalInstanceCount }} treneri</span
          >
        </div>
        <div class="grid">
          <div class="col-12">
            <div
              class="p-5 bg-primary lg:bg-white text-white lg:text-primary rounded shadow text-center"
            >
              <p class="fw-bold">
                UZMANĪBU! 29. jūnijā veiktās treniņu rezervācijas tiek
                automātiski pārceltas no āra laukumiem uz manēžām saistībā ar
                notiekošām treniņsacensībām! Papildu jautājumu gadījumā lūdzam
                sazināties ar savu treneri.
              </p>
            </div>
          </div>
          <DataView
            :layout="'grid'"
            :value="fetchDataStore.allInstances"
            class="col-12 bg-transparent"
          >
            <template v-if="!fetchDataStore.isFetching" #grid="slotProps">
              <div class="grid">
                <div
                  v-for="(instructor, index) in slotProps.items"
                  :key="index"
                  class="col-12 md:col-6 xl:col-4 p-2"
                >
                  <div
                    class="bg-white rounded shadow p-4 rounded flex flex-column h-full"
                  >
                    <div class="flex">
                      <img
                        :src="instructor.user.profile_picture"
                        class="rounded"
                        style="max-width: 7rem"
                      />
                      <div class="w-auto px-3 py-0 align-content-center">
                        <div>
                          <div class="text-lg font-bold text-900">
                            {{
                              instructor.user.name +
                              ` ` +
                              instructor.user.surname
                            }}
                          </div>
                          <span class="font-medium text-secondary text-sm">{{
                            instructor.certificate.category.name
                          }}</span>
                        </div>
                        <div
                          class="surface-100 p-1 mt-3 w-fit"
                          style="border-radius: 30px"
                        >
                          <div
                            class="surface-0 flex align-items-center gap-1 justify-content-center py-1 px-3"
                            style="
                              border-radius: 30px;
                              box-shadow: 0px 1px 2px 0px rgba(0, 0, 0, 0.04),
                                0px 1px 2px 0px rgba(0, 0, 0, 0.06);
                            "
                          >
                            <template v-for="star in 5">
                              <i
                                v-if="star <= instructor.rating"
                                :key="'filled-' + star"
                                class="pi pi-star-fill text-yellow-500"
                              ></i>
                              <i
                                v-else
                                :key="'empty-' + star"
                                class="pi pi-star text-yellow-500"
                              ></i>
                            </template>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="pt-4">
                      <div>
                        <div
                          class="flex flex-row justify-content-between align-items-start gap-2"
                        ></div>
                        <div class="fw-bold text-black">PAR MANI</div>
                        <div>
                          {{ instructor.short_description }}
                        </div>
                      </div>
                    </div>
                    <div class="text-right bottom-0">
                      <Button
                        icon="pi pi-arrow-right"
                        @click="router.push(`/booking/${instructor.id}`)"
                      />
                    </div>
                  </div>
                </div>
              </div>
            </template>
            <template v-if="fetchDataStore.isFetching" #empty>
              <div class="grid">
                <div v-for="i in 5" class="col-12 md:col-6 xl:col-4 p-2">
                  <Skeleton height="20rem" width="100%" />
                </div>
              </div>
            </template>
          </DataView>
        </div>
      </div>
    </template>
  </Transition>
</template>

<script setup>
import { computed, onBeforeMount, onMounted, ref } from "vue";
import { FilterMatchMode, FilterOperator } from "primevue/api";

import { useFetchDataStore } from "@/stores/fetchDataStore";
import router from "@/router/router";

const fetchDataStore = useFetchDataStore();

const instance = computed(() => fetchDataStore.instance);
const instances = computed(() => fetchDataStore.allInstances);
const totalInstances = computed(() => fetchDataStore.totalInstanceCount);

const globalFilterFields = ref(["id", "name"]);
const filters = ref();

const initFilters = () => {
  const defaultTextContainsFilter = () => ({
    operator: FilterOperator.AND,
    constraints: [{ value: null, matchMode: FilterMatchMode.CONTAINS }],
  });

  filters.value = {
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
    id: defaultTextContainsFilter(),
    name: defaultTextContainsFilter(),
  };
};

initFilters();

const onRowSelect = async (event) => {
  await fetchDataStore.fetchInstance("instructors", event.data.id);
  visible.value = true;
};

let visible = ref(false);

onBeforeMount(async () => {
  await fetchDataStore.fetchDatabaseData("instructors");
});

onMounted(() => {
  fetchDataStore.showComponents();
});
</script>

<style scoped>
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
