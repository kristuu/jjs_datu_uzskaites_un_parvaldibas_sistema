<template>
  <div>
    <AdminTable
        v-show="can('manage instructors')"
        :page-name="$t(`pageHeadings.instructors.manage instructors`)"
        :database-table="'instructors'"
        :model-name="'Instructor'"
        :instance-id-column="'id'"
        :short-desc="$t(`pageHeadings.instructors.total`, {total: totalInstances})"
    >
      <DataTable :value="instances" size="small" stripedRows removableSort
                 paginator :rows="10" :rowsPerPageOptions="[10, 15, 20, 50]"
                 v-model:filters="filters" filterDisplay="menu" :globalFilterFields="globalFilterFields"
                 :rowClass="rowClass">
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
        <Column field="user.person_code" :header="$t('table.users.person_code')" sortable>
          <template #body="{ data }">
            {{ `${data.user.person_code.slice(0, 6)}-${data.user.person_code.slice(6, 12)}` }}
          </template>
          <template #filter="{ filterModel }">
            <InputText
                type="text"
                v-model="filterModel.value"/>
          </template>
        </Column>
        <Column field="user.name" :header="$t('table.users.name')" sortable>
          <template #body="{ data }">
            {{ data.user.name }}
          </template>
          <template #filter="{ filterModel }">
            <InputText
                type="text"
                v-model="filterModel.value"/>
          </template>
        </Column>
        <Column field="user.surname" :header="$t('table.users.surname')" sortable>
          <template #body="{ data }">
            {{ data.user.surname }}
          </template>
          <template #filter="{ filterModel }">
            <InputText
                type="text"
                v-model="filterModel.value"/>
          </template>
        </Column>
        <Column field="certificate.category.name" :header="$t('table.instructors.categories.name')" sortable>
          <template #body="{ data }">
            {{ data.certificate.category.name }}
          </template>
          <template #filter="{ filterModel }">
            <InputText
                type="text"
                v-model="filterModel.value"/>
          </template>
        </Column>
        <Column field="certificate.expiration_date" :header="$t('table.instructors.certificates.expiration_date')"
                filterType="date" dataType="date" sortable>
          <template #body="{ data }">
            {{ data.certificate.expiration_date }}
          </template>
          <template #filter="{ filterModel }">
            <Calendar
                v-model="filterModel.value"
                type="date"
                :maxDate="maxDate"
                dateFormat="dd.mm.yy"/>
          </template>
        </Column>
        <Column :exportable="false">
          <template #body>
<!--            <Button icon="bi bi-pencil-fill" outlined rounded class="mr-2"
                    @click="router.push({ name: 'EditInstructor', params: { id: 0 } })"/>-->
            <Button icon="bi bi-trash-fill" @click="" outlined rounded />
          </template>
        </Column>
      </DataTable>
    </AdminTable>
  </div>
</template>

<script setup>
import {computed, onMounted, ref} from 'vue';
import AdminTable from '@/components/AdminTable.vue';
import {FilterMatchMode, FilterOperator} from "primevue/api";
import router from "@/router/router";

import { useStore } from 'vuex';
const store = useStore();

const fetchDatabaseData = async () => {
  await store.dispatch('fetchDatabaseData',  'instructors');
}

const instances = computed(() => store.state.instances);
const totalInstances = computed(() => store.state.totalInstances);

const maxDate = computed(() => {
  let date = new Date();
  date.setFullYear(date.getFullYear() - 1);
  return date;
})

const globalFilterFields = ref([
  'id', 'user.person_code', 'user.name', 'user.surname',
  'certificate.category.name'
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
    "user.person_code": defaultTextContainsFilter(),
    "user.name": defaultTextContainsFilter(),
    "user.surname": defaultTextContainsFilter(),
    "certificate.category.name": defaultTextContainsFilter(),
    "certificate.expiration_date": {
      operator: FilterOperator.AND,
      constraints: [{ value: null, matchMode: FilterMatchMode.DATE_IS }]
    }
  }
};

initFilters();

const parseDate = (dateString) => {
  const [day, month, year] = dateString.split('.');
  return new Date(parseInt(year, 10), parseInt(month, 10) - 1, parseInt(day, 10));
}

const rowClass = (data) => {
  console.log(parseDate(data.certificate.expiration_date));
  return [{
    'bg-red-100': parseDate(data.certificate.expiration_date) < new Date(),
  }]
};

const items = ref([]);

onMounted(() => {
  fetchDatabaseData();
  console.log(instances.value);
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

