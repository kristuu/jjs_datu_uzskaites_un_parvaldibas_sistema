<template>
  <Transition name="fade" mode="out-in">
    <template v-if="fetchDataStore.show">
      <div>
        <div
          class="flex flex-column lg:flex-row align-items-baseline text-primary lg:text-white mb-3"
        >
          <h1 class="fw-bold mb-0">Treniņa rezervācija</h1>
          <span class="ml-2"
            ><i class="bi bi-caret-right-fill" />Pašlaik pieejami 3
            treneri</span
          >
        </div>
        <div class="grid">
          <div class="col-12">
            <div
              class="p-5 bg-primary lg:bg-white text-white lg:text-primary rounded shadow text-center"
            >
              <p class="fw-bold">
                UZMANĪBU! 10. maijā veiktās treniņu rezervācijas tiek
                automātiski pārceltas no āra laukumiem uz manēžām saistībā ar
                notiekošām treniņsacensībām! Papildu jautājumu gadījumā lūdzam
                sazināties ar savu treneri.
              </p>
            </div>
          </div>
          <DataView
            :value="fetchDataStore.allInstances"
            class="col-12 bg-transparent"
            :layout="'grid'"
          >
            <template #grid="slotProps">
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
                      <div
                        class="surface-100 rounded p-3 align-content-center text-center"
                        style="
                          width: 125px;
                          height: 200px;
                          min-width: 125px;
                          min-height: 200px;
                        "
                      >
                        <div class="relative mx-auto">
                          <h3 class="my-6 text-secondary">PROFILA FOTO</h3>
                        </div>
                      </div>
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
                            <i
                              class="pi pi-star-fill text-yellow-500"
                              v-for="i in 3"
                            ></i>
                            <i
                              class="pi pi-star text-yellow-500"
                              v-for="i in 2"
                            ></i>
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
                          {{ instructor.description }}
                        </div>
                      </div>
                    </div>
                    <div class="text-right bottom-0">
                      <Button icon="pi pi-arrow-right" />
                    </div>
                  </div>
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
