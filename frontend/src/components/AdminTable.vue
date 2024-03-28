<script setup>
import { useRoute } from 'vue-router';
import router from "@/router/router";
import { useFetchDataStore } from "@/stores/fetchDataStore";

const fetchDataStore = useFetchDataStore();
const route = useRoute();

const props = defineProps({
  pageName: String,
  shortDesc: String,
  databaseTable: String,
  modelName: String,
});
</script>

<template>
  <div v-if="!fetchDataStore.isLoading">
    <div v-if="can('list instances')">
      <div class="flex flex-column lg:flex-row sm:justify-content-between text-primary lg:text-white mb-3">
        <div class="flex flex-column lg:flex-row align-items-baseline">
          <h1 class="fw-bold mb-0">{{ props.pageName }}</h1>
          <span class="ml-2"><i class="bi bi-caret-right-fill" /> {{ props.shortDesc }} </span>
        </div>
      </div>
      <div class="container-fluid content-card bg-white shadow-lg">
        <slot></slot>
      </div>
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

