<script setup>
import {ref} from 'vue';
import AdminTable from '@/components/AdminTable.vue';
import { useTotalInstances } from "@/hooks/useTotalInstances.js";
import {FilterMatchMode, FilterOperator} from "primevue/api";

const filters = ref();

const initfilters = () => {
  const defaultTextContainsFilter = () => ({
    operator: FilterOperator.AND,
    constraints: [{ value: null, matchMode: FilterMatchMode.CONTAINS }]
  });

  filters.value = {
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
    name: {
      sortable: true, filterType: 'text',
      operator: FilterOperator.AND,
      constraints: [{ value: null, matchMode: FilterMatchMode.CONTAINS }]
    },
    surname: {
      sortable: true, filterType: 'text', dataType: 'text',
      operator: FilterOperator.AND,
      constraints: [{ value: null, matchMode: FilterMatchMode.CONTAINS }]
    },
    person_code: {
      sortable: true, filterType: 'personCode', dataType: 'text',
      operator: FilterOperator.AND,
      constraints: [{ value: null, matchMode: FilterMatchMode.CONTAINS }]
    },
    birthdate: {
      sortable: true, filterType: 'date', dataType: 'date',
      operator: FilterOperator.AND,
      constraints: [{ value: null, matchMode: FilterMatchMode.DATE_IS }]
    },
    email: {
      sortable: true, filterType: 'text', dataType: 'text',
      operator: FilterOperator.AND,
      constraints: [{ value: null, matchMode: FilterMatchMode.CONTAINS }]
    },
    phone: {
      sortable: true, filterType: 'text', dataType: 'text',
      operator: FilterOperator.AND,
      constraints: [{ value: null, matchMode: FilterMatchMode.CONTAINS }]
    },
    iban_code: {
      sortable: true, filterType: 'text', dataType: 'text',
      operator: FilterOperator.AND,
      constraints: [{ value: null, matchMode: FilterMatchMode.CONTAINS }]
    },
  }
};
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

