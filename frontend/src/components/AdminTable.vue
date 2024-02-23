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

const selectedColumns = ref(tableColumns.value);
const onToggle = (val) => {
  selectedColumns.value = tableColumns.value.filter(col => val.includes(col));
}

const fetchDatabaseData = async () => {
  store.commit('setLoading', true);
  try {
    const response = await axios.get(`/${props.databaseTable}`);
    const tempHeadings = await axios.get(`/${props.databaseTable}/columns`);
    tableColumns.value = tempHeadings.data;
    selectedColumns.value = tableColumns.value;
    globalFilterFields.value = response.data.globalFilterFields;
    instances.value = response.data[0];

    // convert json string dates returned from laravel to JS Date objects
    instances.value.forEach((instance) => {
      for (let property in instance) {
        if (!isNaN(Date.parse(instance[property]))) {
          instance[property] = new Date(instance[property]);
        }
      }
    });

    totalInstances.value = response.data.total;
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
      <Button v-if="can('create instances')"
              icon="bi bi-person-add"
              @click="router.push({ name: 'Create' + modelName})" raised>
      </Button>
    </div>
    <div class="mb-2">

    </div>
    <div class="container-fluid content-card bg-white shadow-lg">
      <table id="listTable"></table>
      <DataTable :value="instances" size="small" stripedRows removableSort
                 paginator :rows="10" :rowsPerPageOptions="[10, 15, 20, 50]"
                 v-model:filters="filters" filterDisplay="menu" :globalFilterFields="globalFilterFields">
        <template #header>
          <div class="flex justify-content-between flex-wrap mb-2 mt-2">
            <div style="text-align:left">
              <MultiSelect :maxSelectedLabels="1" :modelValue="selectedColumns" :options="tableColumns" optionLabel="header" @update:modelValue="onToggle"
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
        <Column v-for="(column, index) in selectedColumns" :value="instances" :sortable="props.filterOptions[column.field]?.sortable" :key="column.field + '_' + index" :field="column.field" :header="column.header"
                :filterField="column.field" :dataType="props.filterOptions[column.field]?.dataType">
          <template #body="{ data }">
            {{ props.filterOptions[column.field]?.dataType === 'date' ? formatDate(data[column.field]) : data[column.field] }}
          </template>
          <template #filter="{ filterModel }" v-if="filters[column.field]">
              <InputText
                  v-if="props.filterOptions[column.field].filterType === 'personCode'"
                  v-model="filterModel.value"
                  :type="props.filterOptions[column.field].filterType"
                  :placeholder="'search' + column.header"/>

              <div v-if="props.filterOptions[column.field].filterType === 'text'">
                <InputText
                    v-model="filterModel.value"
                    :type="props.filterOptions[column.field].filterType"
                    :placeholder="'search' + column.header"/>
              </div>

              <div v-if="props.filterOptions[column.field].filterType === 'select'">
                <Dropdown
                    :options="column.options"
                    v-model="filterModel.value"/>
              </div>

              <div v-if="props.filterOptions[column.field].filterType === 'multiSelect'">
                <MultiSelect
                    :options="column.options"
                    v-model="filterModel.value"/>
              </div>

            <Calendar
                v-if="props.filterOptions[column.field].filterType === 'date'"
                type="date"
                v-model="filterModel.value"
                dateFormat="dd.mm.yy"
                :maxDate="new Date()"/>

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

