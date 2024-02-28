<script setup>
import {computed, onMounted, ref} from 'vue';
import { useStore } from 'vuex';
import { useRoute } from 'vue-router';
import axios from '@/services/axios';
import router from "@/router/router";
import {format} from "date-fns";

const route = useRoute();
const store = useStore();
const emit = defineEmits(['update-error-list'])

let formInstance = computed(() => store.state.formInstance);
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

let createInstance = async() => {
  try {
    formatDates(formInstance);
    const response = await axios.post(`/${props.databaseTable}`, formInstance.value)
    console.log(response);
    store.commit("resetFormInstance");
    await router.push(`/admin/${props.databaseTable}`);
  } catch (e) {
    console.error(`Error creating ${props.modelName}: `, e);
    errorList.value = e.response.data;
    emit('update-error-list', errorList.value);
  }
};

let updateInstance = async (instanceId) => {
  try {
    formatDates(formInstance);
    const response = await axios.put(`/${props.databaseTable}/${instanceId}`, formInstance.value);
    console.log(response);
    store.commit("resetFormInstance");
    await router.push(`/admin/${props.databaseTable}`);
  } catch (e) {
    console.error(`Error updating ${props.modelName}: `, e);
    errorList.value = e.response.data;
    emit('update-error-list', errorList.value);
  }
};

const attachFormValidation = () => {
  const forms = document.querySelectorAll('.needs-validation');
  Array.from(forms).forEach(form => {
    form.addEventListener('submit', event => {
      if (!form.checkValidity()) {
        event.preventDefault();
        event.stopPropagation();
      }
      form.classList.add('was-validated');
    }, false);
  });
};

const fetchInstance = async () => {
  store.commit('setLoading', true);
  const id = route.params.id;
  console.log(id);
  if (isUpdateMode) {
    try {
      await store.dispatch('fetchInstance', {
        tableName: props.databaseTable, id
      });
    } catch (error) {
      store.commit('setErrorStatus', error.status);
      store.commit('setErrorMessage', error.data.message);
      await router.push({ name: 'ErrorView' });
    } finally {
      store.commit('setLoading', false);
    }
  }
}

const formatDates = (instance) => {
  Object.keys(instance.value).forEach((key) => {
    if (key === 'birthdate') {
      let date;

      if (instance.value[key] instanceof Date) {
        date = instance.value[key];
      } else {
        // Parse time in the 'dd.mm.yyyy' format
        let parts = instance.value[key].split(".");
        date = new Date(parts[2], parts[1] - 1, parts[0]);
      }

      let year = date.getFullYear();
      let month = (date.getMonth() + 1).toString().padStart(2, '0'); // Ensure month is 2 digits
      let day = date.getDate().toString().padStart(2, '0'); // Ensure day is 2 digits

      instance.value[key] = `${year}-${month}-${day}`;
      console.log(instance.value[key])
    }
  });
}

onMounted(() => {
  fetchInstance();
  attachFormValidation();
});
</script>

<template>
  <div class="mb-3">
    <div class="d-flex align-items-baseline text-white mb-4">
      <h2 class="fw-bold">{{ props.pageName }}</h2>
      <span class="ms-2"><i class="bi bi-caret-right-fill" /> {{ props.shortDesc }} </span>
    </div>
    <div class="container-fluid content-card bg-white shadow-lg mb-2">
      <slot></slot>
    </div>
    <Button label="Saglabāt" icon="bi bi-check-lg" class="mr-2" raised
            @click="isUpdateMode ? updateInstance(route.params.id) : createInstance()" />
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

