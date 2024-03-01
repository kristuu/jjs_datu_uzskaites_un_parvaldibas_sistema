<script setup>
import {ref} from 'vue';
import AdminTable from '@/components/AdminTable.vue';
import { useTotalInstances } from "@/hooks/useTotalInstances.js";
import {FilterMatchMode, FilterOperator} from "primevue/api";

const filterOptions = ref({
  global: { value: null, matchMode: FilterMatchMode.CONTAINS },
  name: {
    sortable: true, filterType: 'text',
    operator: FilterOperator.AND,
    constraints: [{ value: null, matchMode: FilterMatchMode.CONTAINS }]
  },
});
const items = ref([]);

const { totalInstances, handleTotalInstancesUpdate } = useTotalInstances();

</script>

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

