<template>
  <div>
    <AdminTable
        v-if="can('manage roles') && !fetchDataStore.isLoading"
        :page-name="$t(`pageHeadings.roles.manage roles`)"
        :database-table="'roles'"
        :model-name="'Role'"
        :instance-id-column="'id'"
        :short-desc="$t(`pageHeadings.roles.in total x roles`, {total: totalInstances})"
    >
      <div class="p-1">
        <Message
            :closable="false"
            severity="warn"
        >
          <small>Lomu integritātes nolūkos</small>
          <br/>Tabulas saturs pieejams un apstrādājams <strong>TIKAI angļu valodā</strong>!</Message>
      </div>
      <DataTable :value="instances" size="small" stripedRows removableSort
                 paginator :rows="10" :rowsPerPageOptions="[10, 15, 20, 50]"
                 v-model:filters="filters" filterDisplay="menu" :globalFilterFields="globalFilterFields"
                 selectionMode="single" @rowSelect="(e) => { onRowSelect(e.data) }">
        <template #header>
          <div class="flex justify-content-between flex-wrap mb-2 mt-2">
            <IconField iconPosition="left">
              <InputIcon>
                <i class="pi pi-search" />
              </InputIcon>
              <InputText v-model="filters['global'].value" placeholder="Keyword Search" />
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
        <Column field="name" :header="$t('table.roles.name')" sortable>
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
            <Button icon="bi bi-trash-fill" @click="fetchDataStore.deleteInstance(`roles`, data.id)" outlined rounded />
          </template>
        </Column>
      </DataTable>
    </AdminTable>

    <Sidebar v-model:visible="visible" position="bottom" style="height:40rem; max-height: 90vh;">
      <template #container="{ closeCallback }">
        <div class="flex flex-column h-full container">
          <div class="flex align-items-center justify-content-between px-4 pt-3 flex-shrink-0">
            <img src="@/assets/logo-red.svg" width="50" />
            <span class="font-semibold text-2xl text-primary">Lomas apskate</span>
            <Button type="button" @click="closeCallback" icon="pi pi-times" rounded outlined class="h-2rem w-2rem"></Button>
          </div>
          <Divider />
          <div class="overflow-y-auto w-100">
            <div class="row gap-3 container-fluid mx-auto">
              <div class="d-flex flex-column col-lg-3 col-sm-6 col-12">
                <label>ID</label>
                <span>{{ instance.id }}</span>
              </div>
              <div class="d-flex flex-column col-lg-3 col-sm-6 col-12">
                <label>{{ $t(`table.roles.name`) }}</label>
                <span>{{ instance.name }}</span>
              </div>
              <Fieldset legend="Tiesības">
                <div class="row">
                  <template v-for="permission in instance.permissions">
                    <div
                        class="d-flex flex-column col-lg-3 col-sm-4 col-12"
                        v-if="permission.checked"
                    >
                      <Chip
                          :label="permission.name"
                          style="height: 100%;"
                      />
                    </div>
                  </template>
                </div>
              </Fieldset>
            </div>
          </div>
          <div class="mt-auto">
            <hr class="mb-3 mx-3 border-top-1 border-none surface-border" />
            <div class="m-3 flex justify-content-between gap-3 text-primary">
              <router-link v-if="instance.id" :to="{ name: `EditRole`, params: { id: instance.id } }">
                <span class="font-bold"><i class="bi bi-pencil-fill"/> {{ $t(`table.edit`) }}</span>
              </router-link>
              <span class="font-bold cursor-pointer" @click="() => { fetchDataStore.deleteInstance(`roles`, instance.id); visible = false; }">{{ $t(`table.delete`) }} <i class="bi bi-trash-fill"/></span>
            </div>
          </div>
        </div>
      </template>
    </Sidebar>
  </div>
</template>

<script setup>
import {computed, onMounted, ref} from 'vue';
import AdminTable from '@/components/AdminTable.vue';
import {FilterMatchMode, FilterOperator} from "primevue/api";

import { useFetchDataStore } from "@/stores/fetchDataStore";
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
  await fetchDataStore.fetchInstance("roles", event.id);
  visible.value = true;
}

let visible = ref(false);

onMounted(async () => {
  await fetchDataStore.fetchDatabaseData("roles");
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

