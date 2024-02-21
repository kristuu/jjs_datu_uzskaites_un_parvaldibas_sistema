<script setup>
import {onMounted, ref, onUnmounted, watchEffect} from 'vue';
import axios from '@/services/axios';
import { useRoute } from 'vue-router';
import router from "@/router/router";
import { useStore } from 'vuex';

const store = useStore();
const route = useRoute();

// const addToastNotification = inject('addToastNotification');

const props = defineProps({
  pageName: String,
  shortDesc: String,
  databaseTable: String,
  modelName: String,
  headers: Array,
  instanceIdColumn: String,
  createIconClass: String,
});

const emit = defineEmits({
  'update:totalInstances': null,
});

let instances = ref([]);
let tableColumns = ref([]);

let totalInstances = ref([]);

const fetchDatabaseData = async () => {
  store.commit('setLoading', true);
  try {
    const response = await axios.get(`/${props.databaseTable}`);
    const tempHeadings = await axios.get(`/${props.databaseTable}/columns`);
    tableColumns.value = tempHeadings.data;
    instances.value = response.data;
    totalInstances.value = response.data.total;
    console.log(tableColumns.value);
    emit('update:totalInstances', totalInstances.value);
  } catch (e) {
    console.error(`Error fetching ${props.databaseTable}: `, e);
  } finally {
    store.commit('setLoading', false);
  }
}

// const deleteInstance = async (instanceId) => {
//   try {
//     const response = await axios.delete(`/${props.databaseTable}/${instanceId}`);
//     console.log(response);
//     addToastNotification({
//       status: response.status,
//       title: response.status + ' statuss',
//       message: response.data.message,
//     });
//     await fetchDatabaseData();
//   } catch (e) {
//     console.error(`Error fetching ${props.databaseTable}: `, e);
//   }
// }

watchEffect(() => {
  const { params } = route;

  if (params?.databaseTable != null) {
    fetchDatabaseData();
  }
});

onMounted(() => {
  fetchDatabaseData();


});

onUnmounted(() => {
  fetchDatabaseData();
});
</script>

<template>
  <div>
    <div class="d-flex justify-content-between text-white mb-3">
      <div class="d-flex align-items-baseline">
        <h2 class="fw-bold">{{ props.pageName }}</h2>
        <span class="ms-2"><i class="bi bi-caret-right-fill" /> {{ props.shortDesc }} </span>
      </div>
      <button v-if="can('create instances')" class="btn btn-primary" @click="router.push({ name: 'Create' + modelName})">
        <i class="bi bi-person-add" style="font-size: 24px;"></i>
      </button>
    </div>
    <div class="mb-2">

    </div>
    <div class="container-fluid content-card bg-white shadow-lg">
      <table id="listTable"></table>
      <DataTable :value="instances" size="normal" stripedRows showGridlines removableSort
                 paginator :rows="5" :rowsPerPageOptions="[5, 10, 20, 50]">
          <Column v-for="(data) in tableColumns" sortable :key="data.database" :field="data.database" :header="data.translated"></Column>
      </DataTable>
    </div>
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

