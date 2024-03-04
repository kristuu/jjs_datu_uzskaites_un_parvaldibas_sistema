<script setup>
import {onMounted, ref, onUnmounted, watchEffect, watch, computed} from 'vue';
import axios from '@/services/axios';
import { useRoute } from 'vue-router';
import router from "@/router/router";
import { useStore } from 'vuex';

import get from 'lodash/get';

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

let globalTranslateColumns = ref(['id', 'created_at', 'updated_at']);
const selectedColumns = ref();

const onToggle = (val) => {
  selectedColumns.value = tableColumns.value.filter(col => val.includes(col));
}

const fetchDatabaseData = async () => {
  store.commit('setLoading', true);
  let flattenedInstances = [];
  try {
    const response = await axios.get(`/${props.databaseTable}`);
    console.log(response);
    // const tempHeadings = await axios.get(`/${props.databaseTable}/columns`);
    flattenedInstances = response.data.instances.map(instance => flattenObject(instance))

    // dynamic column retrieval
    if (flattenedInstances.length > 0) {
      tableColumns.value = Object.keys(flattenedInstances[0]);
    }
    console.log(flattenedInstances);
    instances = response.data.instances;
    selectedColumns.value = tableColumns.value.slice(0, 5);
    globalFilterFields.value = response.data.globalFilterFields;
    instances.value = response.data.instances;

    totalInstances.value = response.data.total;
    emit('update:totalInstances', totalInstances.value);
  } catch (e) {
    console.error(`Error fetching ${props.databaseTable}: `, e);
  } finally {
    store.commit('setLoading', false);
  }
};

const deleteInstance = async (instanceId) => {
  try {
    const response = await axios.delete(`/${props.databaseTable}/${instanceId}`);
    console.log(response);
    await fetchDatabaseData();
  } catch (e) {
    console.error(`Error fetching ${props.databaseTable}: `, e);
  }
};

const truncateText = (text, maxLength) => {
  return text.length > maxLength ? text.slice(0, maxLength) + '...' : text;
}

const initFilters = () => {
  filters.value = props.filterOptions;
};

/**
 * Flatten a nested object into a single-level object.
 */
const flattenObject = (obj) => {
  let toReturn = {};

  for (let i in obj) {
    if (!obj.hasOwnProperty(i)) continue;
    if ((typeof obj[i]) == 'object') {
      let flatObject = flattenObject(obj[i]);
      for (let x in flatObject) {
        if (!flatObject.hasOwnProperty(x)) continue;
        toReturn[i + '.' + x] = flatObject[x];
      }
    } else {
      toReturn[i] = obj[i];
    }
  }
  return toReturn;
};

const getDataValue = (data, path) => {
  return get(data, path);
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
  <div v-if="can('list instances')">
    <div class="flex justify-content-between text-white mb-3">
      <div class="flex align-items-baseline">
        <h1 class="fw-bold">{{ props.pageName }}</h1>
        <span class="ml-2"><i class="bi bi-caret-right-fill" /> {{ props.shortDesc }} </span>
      </div>
      <Button v-if="can('create instances')"
              icon="bi bi-plus-lg"
              rounded raised
              @click="router.push({ name: 'Create' + modelName})">
      </Button>
    </div>
    <div class="container-fluid content-card bg-white shadow-lg">
      <table id="listTable"></table>
      <DataTable :value="instances" size="small" stripedRows removableSort
                 :columns="tableColumns"
                 paginator :rows="10" :rowsPerPageOptions="[10, 15, 20, 50]"
                 v-model:filters="filters" filterDisplay="menu" :globalFilterFields="globalFilterFields">
        <template #header>
          <div class="flex justify-content-between flex-wrap mb-2 mt-2">
            <div style="text-align:left">
              <MultiSelect :maxSelectedLabels="1" :modelValue="selectedColumns" :options="tableColumns" :optionLabel="option => globalTranslateColumns.includes(option) ? $t(`table.${option}`) : $t(`table.${props.databaseTable}.${option}`)" @update:modelValue="onToggle"
                           display="chip" placeholder="Select Columns" />
            </div>
            <IconField iconPosition="left">
              <InputIcon>
                <i class="pi pi-search" />
              </InputIcon>
              <InputText v-model="filters['global'].value" placeholder="Keyword Search" />
            </IconField>
          </div>
        </template>
        <Column v-for="(column, index) in selectedColumns" :value="instances" :sortable="props.filterOptions[column]?.sortable" :key="column + '_' + index" :field="column" :header="globalTranslateColumns.includes(column) ? $t(`table.${column}`) : $t(`table.${props.databaseTable}.${column}`)"
                :filterField="column" :dataType="props.filterOptions[column]?.dataType">
          <template #body="{ data }">
            {{ getDataValue(data, column) }}
          </template>
          <template #filter="{ filterModel }" v-if="filters[column]">
            <InputText
                v-if="props.filterOptions[column].filterType === 'personCode'"
                v-model="filterModel.value"
                :type="props.filterOptions[column].filterType"
                :placeholder="'search' + column"/>

            <div v-if="props.filterOptions[column].filterType === 'text'">
              <InputText
                  v-model="filterModel.value"
                  :type="props.filterOptions[column].filterType"
                  :placeholder="'Meklēt pēc ' + column"/>
            </div>

            <div v-if="props.filterOptions[column].filterType === 'select'">
              <Dropdown
                  :options="column.options"
                  v-model="filterModel.value"/>
            </div>

            <div v-if="props.filterOptions[column].filterType === 'multiSelect'">
              <MultiSelect
                  :options="column.options"
                  v-model="filterModel.value"/>
            </div>

            <Calendar
                v-if="props.filterOptions[column].filterType === 'date'"
                type="date"
                v-model="filterModel.value"
                dateFormat="dd.mm.yy"
                :maxDate="new Date()"/>

              <!-- Add more `v-else-if` statements as needed for other filter types -->
          </template>
        </Column>
        <Column :exportable="false">
          <template #body="{ data }">
            <Button icon="bi bi-pencil-fill" outlined rounded class="mr-2"
                    @click="router.push({ name: 'Edit' + modelName, params: { id: data[props.instanceIdColumn] } })"/>
            <Button icon="bi bi-trash-fill" @click="deleteInstance(data[instanceIdColumn])" outlined rounded />
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

  td {
    display: -webkit-box;
    overflow: hidden;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
  }
</style>

