<script setup>
import {ref} from 'vue';
import AdminTable from '@/components/AdminTable.vue';
import { useTotalInstances } from "@/hooks/useTotalInstances.js";
import {FilterMatchMode, FilterOperator} from "primevue/api";

import { useFetchDataStore } from "@/stores/fetchDataStore";
const fetchDataStore = useFetchDataStore();

const filters = ref();

const initfilters = () => {
  const defaultTextContainsFilter = () => ({
    operator: FilterOperator.AND,
    constraints: [{ value: null, matchMode: FilterMatchMode.CONTAINS }]
  });

  filters.value = {
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
    name: defaultTextContainsFilter(),
    surname: defaultTextContainsFilter(),
    person_code: defaultTextContainsFilter(),
    birthdate: {
      sortable: true, filterType: 'date', dataType: 'date',
      operator: FilterOperator.AND,
      constraints: [{ value: null, matchMode: FilterMatchMode.DATE_IS }]
    },
    email: defaultTextContainsFilter(),
    phone: defaultTextContainsFilter(),
    iban_code: defaultTextContainsFilter(),
  }
};

initfilters();

const items = ref([]);

const { totalInstances, handleTotalInstancesUpdate } = useTotalInstances();

</script>

<template>
  <div>
    <AdminTable
        v-show="can('manage users')"
        :page-name="$t(`pageHeadings.users.manage users`)"
        :database-table="'users'"
        :model-name="'User'"
        :instance-id-column="'person_code'"
        :short-desc="$t(`pageHeadings.users.in total x users`, {total: totalInstances})"
        :filterOptions="filterOptions"
        @updateItems="newItems => items.value = newItems"
        @update:totalInstances="handleTotalInstancesUpdate"
    />
  </div>
</template>

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

