<script setup>
import {onMounted, ref, onUnmounted, watchEffect, watch, computed} from 'vue';
import axios from '@/services/axios';
import { useRoute } from 'vue-router';
import router from "@/router/router";
import { useStore } from 'vuex';

import get from 'lodash/get';
import {uniq} from "lodash";

const store = useStore();
const route = useRoute();

// const addToastNotification = inject('addToastNotification');

const props = defineProps({
  pageName: String,
  shortDesc: String,
  databaseTable: String,
  modelName: String,
});

const emit = defineEmits({
  'update:totalInstances': null,
});

// let globalTranslateColumns = ref(['id', 'created_at', 'updated_at']);
// const selectedColumns = ref();

// const onToggle = (val) => {
//   selectedColumns.value = tableColumns.value.filter(col => val.includes(col));
// }

/*
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
};*/

/*const getDataValue = (data, path) => {
  return get(data, path);
}*/
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
      <slot></slot>
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

