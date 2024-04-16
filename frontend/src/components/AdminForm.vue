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


let errorList = ref({});

const props = defineProps({
  pageName: String,
  shortDesc: String,
  databaseTable: String,
  modelName: String,
  personCode: String,
  headers: Array,
});

const isUpdateMode = !!route.params.id;
console.log(isUpdateMode);

onBeforeMount(async () => {
  if (!isUpdateMode) {
    fetchDataStore.resetInstance();
  } else {
    await fetchDataStore.fetchInstance(props.databaseTable, route.params.id);
  }
  errorStore.resetErrorList();
});

onMounted(() => {
  fetchDataStore.showComponents();
})
</script>

<template>
  <Toast />
  <Transition name="fade" mode="out-in">
    <div v-if="fetchDataStore.show">
      <div class="mb-3" v-if="isUpdateMode ? can('edit instances') : can('create instances')">
        <div class="d-flex align-items-baseline text-primary lg:text-white mb-4">
          <div class="flex flex-column lg:flex-row align-items-baseline">
            <h1 class="fw-bold mb-0">{{ props.pageName }}</h1>
            <span class="ml-2"><i class="bi bi-caret-right-fill" /> {{ props.shortDesc }} </span>
          </div>
        </div>
        <div class="container-fluid content-card bg-white shadow-lg mb-2">
          <div class="p-2">
            <slot></slot>
          </div>
        </div>
        <Button label="Saglabāt" icon="bi bi-check-lg" class="mr-2" raised
                @click="isUpdateMode ? fetchDataStore.updateInstance(props.databaseTable, route.params.id) : fetchDataStore.createInstance(databaseTable)" />
        <Button severity="secondary" label="Atcelt" icon="bi bi-x-lg" text
                @click="router.go(-1);" />
      </div>
    </div>
  </Transition>
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

