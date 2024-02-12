<script setup>
import {ref} from 'vue';
import AdminTable from '@/components/AdminTable.vue';
// Your other imports...

const headers = ref([
  {key: 'person_code', label: 'Personas kods'},
  {key: 'name', label: 'Vārds (-i)'},
  {key: 'surname', label: 'Uzvārds (-i)'},
  {key: 'email', label: 'E-pasts'},
  {key: 'phone', label: 'Tel. nr.'},
]);
const items = ref([]);

// Your other methods...
</script>

<template>
  <div>
    <AdminTable
        :page-name="`Lietotāju pārvaldība`"
        :database-table="'users'"
        :model-name="'User'"
        :instance-id-column="'person_code'"
        :headers="headers"
        @updateItems="newItems => items.value = newItems"
    />

    <!-- Rest of your code -->
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

