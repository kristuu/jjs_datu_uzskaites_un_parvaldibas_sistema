<script setup>
import {ref} from 'vue';
import AdminTable from '@/components/AdminTable.vue';
import { useTotalInstances } from "@/hooks/useTotalInstances.js";

const headers = ref([
  {key: 'person_code', label: 'Personas kods'},
  {key: 'name', label: 'Vārds (-i)'},
  {key: 'surname', label: 'Uzvārds (-i)'},
  {key: 'email', label: 'E-pasts'},
  {key: 'phone', label: 'Tel. nr.'},
]);
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
        :headers="headers"
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

