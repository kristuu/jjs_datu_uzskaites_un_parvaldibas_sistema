<template>
  <div>
    <AdminTable
        v-show="can('manage countries')"
        :page-name="$t(`pageHeadings.countries.manage countries`)"
        :database-table="'countries'"
        :model-name="'Country'"
        :instance-id-column="'id'"
        :short-desc="$t(`pageHeadings.countries.in total x countries`, {total: totalInstances})"
        :filterOptions="filterOptions"
    >
      <DataTable :value="instances" size="small" stripedRows removableSort
                 paginator :rows="10" :rowsPerPageOptions="[10, 15, 20, 50]"
                 v-model:filters="filters" filterDisplay="menu" :globalFilterFields="globalFilterFields"
                 selectionMode="single" @rowSelect="(e) => { onRowSelect(e) }">
        <template #header>
          <div class="d-flex justify-content-between flex-wrap mb-2 mt-2">
            <Button v-if="can('create instances')"
                    icon="bi bi-plus-lg"
                    rounded raised
                    @click="router.push({ name: 'CreateCountry' })">
            </Button>
            <IconField iconPosition="left">
              <InputIcon>
                <i class="bi bi-search" />
              </InputIcon>
              <InputText v-model="filters['global'].value" :placeholder="$t(`table.search`)" />
            </IconField>
          </div>
        </template>
        <template #empty>NAV IERAKSTU</template>
        <Column field="id" :header="$t('table.id')">
          <template #body="{ data }">
            {{ data.id }}
          </template>
          <template #filter="{ filterModel }">
            <InputText
                type="text"
                v-model="filterModel.value"/>
          </template>
        </Column>
        <Column field="name" :header="$t('table.countries.name')" sortable>
          <template #body="{ data }">
            {{ data.name }}
          </template>
          <template #filter="{ filterModel }">
            <InputText
                type="text"
                v-model="filterModel.value"/>
          </template>
        </Column>
        <Column :exportable="false">
          <template #body="{ data }">
            <!--            <Button icon="bi bi-pencil-fill" outlined rounded class="mr-2"
                                @click="router.push({ name: 'EditInstructor', params: { id: 0 } })"/>-->
            <Button icon="bi bi-trash-fill" @click="fetchDataStore.deleteInstance(`countries`, data.id)" outlined rounded />
          </template>
        </Column>
      </DataTable>
    </AdminTable>

    <Sidebar v-model:visible="visible" position="bottom" style="height:20rem; max-height: 90vh;">
      <template #container="{ closeCallback }">
        <div class="flex flex-column h-full container">
          <div class="flex align-items-center justify-content-between px-4 py-3 flex-shrink-0">
                        <span class="inline-flex align-items-center gap-2">
                            <img src="@/assets/logo-red.svg" width="50" />
                            <span class="font-semibold text-2xl text-primary ml-3">Valsts apskate</span>
                        </span>
            <span>
                            <Button type="button" @click="closeCallback" icon="pi pi-times" rounded outlined class="h-2rem w-2rem"></Button>
                        </span>
          </div>
          <div class="overflow-y-auto w-100">
            <div class="row gap-3 container-fluid mx-auto">
              <div class="d-flex flex-column col-12 sm:col-6 lg:col-3">
                <label>ID</label>
                <span>{{ instance.id }}</span>
              </div>
              <div class="d-flex flex-column col-12 sm:col-6 lg:col-3">
                <label>{{ $t(`table.countries.name`) }}</label>
                <span>{{ instance.name }}</span>
              </div>
            </div>
          </div>
          <div class="mt-auto">
            <hr class="mb-3 mx-3 border-top-1 border-none surface-border" />
            <div class="m-3 flex justify-content-between text-primary">
              <router-link v-if="instance.id" :to="{ name: `EditCountry`, params: { id: instance.id } }">
                <span class="font-bold"><i class="bi bi-pencil-fill"/> {{ $t(`table.edit`) }}</span>
              </router-link>
              <span class="font-bold cursor-pointer" @click="() => { fetchDataStore.deleteInstance(`countries`, instance.id); visible = false; }">{{ $t(`table.delete`) }} <i class="bi bi-trash-fill"/></span>
            </div>
          </div>
        </div>
      </template>
    </Sidebar>
  </div>
</template>

<script setup>
import {computed, onBeforeMount, ref} from 'vue';
import AdminTable from '@/components/AdminTable.vue';
import {FilterMatchMode, FilterOperator} from "primevue/api";

import { useFetchDataStore } from "@/stores/fetchDataStore";
import router from "@/router/router";
const fetchDataStore = useFetchDataStore();

const instance = computed(() => fetchDataStore.instance);
const instances = computed(() => fetchDataStore.allInstances);
const totalInstances = computed(() => fetchDataStore.totalInstanceCount);

const globalFilterFields = ref([
  'id', 'name',
]);
const filters = ref();

const initFilters = () => {
  const defaultTextContainsFilter = () => ({
    operator: FilterOperator.AND,
    constraints: [{ value: null, matchMode: FilterMatchMode.CONTAINS }]
  });

  filters.value = {
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
    "id": defaultTextContainsFilter(),
    "name": defaultTextContainsFilter(),
  }
};

initFilters();

const onRowSelect = async (event) => {
  await fetchDataStore.fetchInstance("countries", event.data.id);
  visible.value = true;
}

let visible = ref(false);

onBeforeMount(async () => {
  await fetchDataStore.fetchDatabaseData("countries");
});
</script>

<style scoped>
p {
  margin: 0;
}

th, h5 {
  font-weight: bold;
}

nav ul {
  margin-bottom: 0 !important;
}
</style>

