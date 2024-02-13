<script setup>
import {computed, inject, onMounted, ref} from 'vue';
import { useStore } from 'vuex';
import axios from '@/services/axios';
import router from "@/router/router";

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
  instance: Array,
  headers: Array,
});

// let isLoading = ref(false);
const isUpdateMode = computed(() =>
  formInstance.value && formInstance.value.length > 0
);

let createInstance = async() => {
  try {
    const response = await axios.post(`/${props.databaseTable}`, formInstance.value)
    console.log(response);
    addToastNotification({
      status: response.status,
      title: response.status + ' statuss',
      message: response.data.data.message,
    });
    await this.$router.push({name: `/${props.databaseTable}`})
  } catch (e) {
    console.error(`Error creating ${props.modelName}: `, e);
    errorList.value = e.response.data;
    emit('update-error-list', errorList.value);
    console.log(errorList.value);
  }
};

let updateInstance = async (instanceId) => {
  try {
    const response = await axios.put(`/${props.databaseTable}/${instanceId}`);
    console.log(response);
    addToastNotification({
      status: response.status,
      title: response.status + ' statuss',
      message: response.data.data.message,
    });
    await router.push({name: `/${props.databaseTable}`})
  } catch (e) {
    console.error(`Error updating ${props.modelName}: `, e);
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

onMounted(() => {
  attachFormValidation();
});
</script>

<template>
  <div class="d-flex align-items-baseline text-white mb-4">
    <h2 class="fw-bold">{{ props.pageName }}</h2>
    <span class="ms-2">>> {{ props.shortDesc }}</span>
  </div>
  <div class="container-fluid content-card bg-white shadow-lg mb-2">
    <slot></slot>
  </div>
  <button class="btn btn-primary btn" @click="isUpdateMode ? updateInstance() : createInstance()">
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

