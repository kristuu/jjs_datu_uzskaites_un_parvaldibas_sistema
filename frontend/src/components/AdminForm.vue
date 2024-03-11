<script setup>
import {computed, onBeforeMount, onMounted, ref} from 'vue';
import { useFetchDataStore } from '@/stores/fetchDataStore';
import { useErrorStore} from "@/stores/errorStore";
import {onBeforeRouteLeave, useRoute} from 'vue-router';
import { useToast } from 'primevue/usetoast';
import { useI18n } from 'vue-i18n';
import router from "@/router/router";

const route = useRoute();
const fetchDataStore = useFetchDataStore();
const errorStore = useErrorStore();
const toast = useToast();
const locale = useI18n();
const emit = defineEmits(['update-error-list'])


let errorList = ref({});

const props = defineProps({
  pageName: String,
  shortDesc: String,
  databaseTable: String,
  modelName: String,
  personCode: String,
  instance: Array,
  headers: Array,
});

const isUpdateMode = !!route.params.id;
console.log(isUpdateMode);

onBeforeMount(() => {
  if (!isUpdateMode) {
    fetchDataStore.resetInstance();
  } else {
    fetchDataStore.fetchInstance(props.databaseTable, route.params.id);
  }
  errorStore.resetErrorList();
});
</script>

<template>
  <Toast />
  <div class="mb-3" v-if="isUpdateMode ? can('edit instances') : can('create instances')">
    <div class="d-flex align-items-baseline text-white mb-4">
      <h2 class="fw-bold">{{ props.pageName }}</h2>
      <span class="ms-2"><i class="bi bi-caret-right-fill" /> {{ props.shortDesc }} </span>
    </div>
    <div class="container-fluid content-card bg-white shadow-lg mb-2">
      <slot></slot>
    </div>
    <Button label="Saglabāt" icon="bi bi-check-lg" class="mr-2" raised
            @click="isUpdateMode ? fetchDataStore.updateInstance(props.databaseTable, route.params.id) : fetchDataStore.createInstance(databaseTable)" />
    <Button severity="secondary" label="Atcelt" icon="bi bi-x-lg" text outlined
            @click="router.go(-1);" />
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

