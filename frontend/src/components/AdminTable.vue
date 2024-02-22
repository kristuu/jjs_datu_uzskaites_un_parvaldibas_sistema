<script setup>
import {onMounted, ref, onUnmounted, watchEffect, watch} from 'vue';
import axios from '@/services/axios';
import { useRoute } from 'vue-router';
import router from "@/router/router";
import { useStore } from 'vuex';
import {FilterMatchMode, FilterOperator} from "primevue/api";
import {isDate} from "date-fns";

const store = useStore();
const route = useRoute();

// const addToastNotification = inject('addToastNotification');

const props = defineProps({
  pageName: String,
  shortDesc: String,
  databaseTable: String,
  modelName: String,
  filterOptions: Object,
  instanceIdColumn: String,
  createIconClass: String,
});

const emit = defineEmits({
  'update:totalInstances': null,
});

let instances = ref([]);
let tableColumns = ref([]);
let globalFilterFields = ref([]);
let filters = ref();

let totalInstances = ref([]);

const fetchDatabaseData = async () => {
  store.commit('setLoading', true);
  try {
    const response = await axios.get(`/${props.databaseTable}`);
    const tempHeadings = await axios.get(`/${props.databaseTable}/columns`);
    console.log(response);
    tableColumns.value = tempHeadings.data;
    globalFilterFields.value = response.data.globalFilterFields;
    instances.value = response.data[0];
    instances.value.forEach((instance) => {
      // console.log(instance);
      // instance.birthdate = new Date(instance.birthdate);
      for (let property in instance) {
        if (!isNaN(Date.parse(instance[property]))) {
          instance[property] = new Date(instance[property]);
        }
      }
    });
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


const formatDate = (value) => {
  return new Date(value).toLocaleDateString('lv', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric'
  });
};

const initFilters = () => {
  filters.value = props.filterOptions;
}

initFilters();

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
      <DataTable :value="instances" size="small" class="" stripedRows removableSort
                 paginator :rows="10" :rowsPerPageOptions="[10, 15, 20, 50]"
                 v-model:filters="filters" filterDisplay="menu" :globalFilterFields="globalFilterFields">
        <template #header>
          <div class="flex justify-content-end">
            <IconField iconPosition="left">
              <InputIcon>
                <i class="pi pi-search" />
              </InputIcon>
              <InputText v-model="filters['global'].value" placeholder="Keyword Search" />
            </IconField>
          </div>
        </template>
        <Column v-for="(column) in tableColumns" :value="instances" :sortable="props.filterOptions[column.database]?.sortable" :key="column.database" :field="column.database" :header="column.translated"
                :filterField="column.database" :dataType="props.filterOptions[column.database]?.dataType">
          <template #body="{ data }">
            {{ props.filterOptions[column.database]?.dataType === 'date' ? formatDate(data[column.database]) : data[column.database] }}
          </template>
          <template #filter="{ filterModel }" v-if="filters[column.database]">
              <InputText
                  v-if="props.filterOptions[column.database].filterType === 'personCode'"
                  v-model="filterModel.value"
                  :type="props.filterOptions[column.database].filterType"
                  :placeholder="'search' + column.translated"/>

              <div v-if="props.filterOptions[column.database].filterType === 'text'">
                <InputText
                    v-model="filterModel.value"
                    :type="props.filterOptions[column.database].filterType"
                    :placeholder="'search' + column.translated"/>
              </div>

              <div v-if="props.filterOptions[column.database].filterType === 'select'">
                <Dropdown
                    :options="column.options"
                    v-model="filterModel.value"/>
              </div>

              <div v-if="props.filterOptions[column.database].filterType === 'multiSelect'">
                <MultiSelect
                    :options="column.options"
                    v-model="filterModel.value"/>
              </div>

            <Calendar
                v-if="props.filterOptions[column.database].filterType === 'date'"
                type="date"
                v-model="filterModel.value"
                dateFormat="mm.dd.yy"
                :maxDate="new Date()"
                @input="console.log(filterModel.value.toDateString())"/>

              <!-- Add more `v-else-if` statements as needed for other filter types -->
            </template>
          </Column>
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

