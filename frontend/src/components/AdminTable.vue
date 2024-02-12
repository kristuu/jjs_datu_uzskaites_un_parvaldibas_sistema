<script setup>
import {onMounted, ref, watch, inject} from 'vue';
import axios from '@/services/axios';
import router from "@/router/router";

const addToastNotification = inject('addToastNotification');

const props = defineProps({
  pageName: String,
  databaseTable: String,
  modelName: String,
  headers: Array,
  instanceIdColumn: String,
  createIconClass: String,
});

let instances = ref([]);

let currentPage = ref(1);
let totalPages = ref(0);
let perPage = ref(12);

let totalInstances = ref([]);
let isLoading = ref(false);

const fetchDatabaseData = async () => {
  isLoading.value = true;
  try {
    const response = await axios.get(`/${props.databaseTable}?page=${currentPage.value}&perPage=${perPage.value}`);
    // Assuming that you want to set response to items
    console.log(response);
    instances.value = response.data.data.data;
    totalInstances.value = response.data.data.total;
    currentPage.value = response.data.data.current_page;
    totalPages.value = response.data.data.last_page;
  } catch (e) {
    console.error(`Error fetching ${props.databaseTable}: `, e);
  }
  isLoading.value = false;
}

const deleteInstance = async (instanceId) => {
  try {
    const response = await axios.delete(`/${props.databaseTable}/${instanceId}`);
    console.log(response);
    addToastNotification({
      status: response.status,
      title: response.status + ' statuss',
      message: response.data.data.message,
    });
    await fetchDatabaseData();
  } catch (e) {
    console.error(`Error fetching ${props.databaseTable}: `, e);
  }
}

watch(currentPage, fetchDatabaseData);

onMounted(fetchDatabaseData);
</script>

<template>
  <div class="d-flex align-items-baseline text-white mb-2">
    <h2 class="fw-bold">{{ props.pageName }}</h2>
    <span class="ms-2">Pavisam kopā <strong>{{ totalInstances }}</strong> lietotāju</span>
  </div>
  <div class="mb-2">
    <button class="btn btn-primary me-2" @click="router.push({ name: 'Create' + modelName})">
      <i class="bi bi-person-add" style="font-size: 24px;"></i>
    </button>
  </div>
  <div class="container-fluid content-card bg-white shadow-lg">
    <div class="table-responsive">
      <table class="table small table-hover">
        <caption>
          <div class="d-flex justify-content-between">
            <span @change="fetchDatabaseData">Lapa {{ currentPage }} no {{ totalPages }}</span>
            <!--      Pagination-->
            <nav aria-label="Tabulas navigācija" v-if="totalInstances > 1">
              <div class="d-flex align-items-center">
                <div class="input-group input-group-sm">
                  <label class="input-group-text" for="entriesPerPage">Ieraksti lapā:</label>
                  <select v-model="perPage" id="entriesPerPage" class="form-select" @change="fetchDatabaseData">
                    <option selected value="12">12</option>
                    <option value="24">24</option>
                    <option value="36">36</option>
                    <option value="48">48</option>
                  </select>
                </div>
                <ul class="pagination ms-2">
                  <li class="page-item ms-auto">
                    <button class="btn btn-sm btn-primary"
                            @click="currentPage = 1"
                            :disabled="currentPage <= 1">
                      <i class="bi bi-chevron-bar-left"></i>
                    </button>
                  </li>
                  <li class="page-item">
                    <button class="btn btn-sm btn-primary"
                            @click="currentPage > 1 ? currentPage-- : null"
                            :disabled="currentPage === 1">
                      <i class="bi bi-chevron-left"></i>
                    </button>
                  </li>
                  <li class="page-item">
                    <button class="btn btn-sm btn-primary"
                            @click="currentPage < totalPages ? currentPage++ : null"
                            :disabled="currentPage === totalPages">
                      <i class="bi bi-chevron-right"></i>
                    </button>
                  </li>
                  <li class="page-item me-auto">
                    <button class="btn btn-sm btn-primary"
                            @click="currentPage = totalPages"
                            :disabled="currentPage >= totalPages">
                      <i class="bi bi-chevron-bar-right"></i>
                    </button>
                  </li>
                </ul>
              </div>
            </nav>
          </div>
        </caption>
        <!-- Table header -->
        <thead class="table-light">
        <th v-for="header in headers" :key="header.label"
            scope="col">{{ header.label.toUpperCase() }}</th>
        <th scope="col" class="text-end">DARBĪBAS</th>
        </thead>

        <!-- Table body -->
        <tbody>
        <template v-if="isLoading">
          <tr>
            <td colspan="8">
              <div class="d-flex justify-content-center align-items-center">
                <div class="spinner-border me-2" aria-hidden="true"></div>
                <strong role="status">Atgūst datus...</strong>
              </div>
            </td>
          </tr>
        </template>
        <template v-else>
          <tr v-for="instance in instances" :key="instance[instanceIdColumn]">
            <td v-for="header in headers" :key="header.label">
              {{ instance[header.key] }}</td>
            <!-- Actions -->
            <td style="text-align: right;">
              <button class="btn btn-sm btn-primary me-2" type="button">
                <i class="bi bi-three-dots"></i>
              </button>
              <button class="btn btn-sm btn-warning me-2" @click="router.push({ name: 'Edit' + modelName, params: { [instanceIdColumn]: instance[instanceIdColumn] } })">
                <i class="bi bi-pencil-square"></i>
              </button>
              <button class="btn btn-sm btn-danger"
                      @click="deleteInstance(instance[instanceIdColumn])">
                <i class="bi bi-trash3-fill"></i>
              </button>
            </td>
          </tr>
        </template>
        </tbody>
      </table>
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

