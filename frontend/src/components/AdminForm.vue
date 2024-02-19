<script setup>
import {inject, computed, onMounted, ref} from 'vue';
import { useStore } from 'vuex';
import { useRoute } from 'vue-router';
import axios from '@/services/axios';
import router from "@/router/router";

const route = useRoute();
const store = useStore();
const emit = defineEmits(['update-error-list'])

let formInstance = computed(() => store.state.formInstance);
let errorList = ref({});

const addToastNotification = inject('addToastNotification');

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
    const response = await axios.post(`/${props.databaseTable}`, formInstance.value)
    console.log(response);
    addToastNotification({
      status: response.status,
      title: response.status + ' statuss',
      message: response.data.message,
    });
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
    const response = await axios.put(`/${props.databaseTable}/${instanceId}`, formInstance.value);
    console.log(response);
    addToastNotification({
      status: response.status,
      title: response.status + ' statuss',
      message: response.data.message,
    });
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
      formInstance.value.masked_person_code = formInstance.value.person_code;
    } catch (error) {
      store.commit('setErrorStatus', error.status);
      store.commit('setErrorMessage', error.data.message);
      await router.push({ name: 'ErrorView' });
    } finally {
      store.commit('setLoading', false);
    }
  }
}

onMounted(() => {
  fetchInstance();
  attachFormValidation();
});
</script>

<template>
      <div class="d-flex align-items-baseline text-white mb-4">
        <h2 class="fw-bold">{{ props.pageName }}</h2>
        <span class="ms-2"><i class="bi bi-caret-right-fill" /> {{ props.shortDesc }} </span>
      </div>
      <div class="container-fluid content-card bg-white shadow-lg mb-2">
        <slot></slot>
      </div>
      <button class="btn btn-primary btn" @click="isUpdateMode ? updateInstance(route.params.id) : createInstance()">
        Saglabāt
      </button>
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

