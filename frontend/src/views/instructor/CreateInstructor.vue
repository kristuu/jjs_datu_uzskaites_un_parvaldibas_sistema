<template>
<AdminForm :page-name="$t(`pageHeadings.instructors.instructors`)"
           :short-desc="$t(`pageHeadings.instructors.create instructor`)"
           :model-name="`Instructor`"
           :database-table="`instructors`"
           @update-error-list="handleErrorListUpdate">
  <form id="createUserForm" class="row gap-3 py-3 text-start needs-validation">
    <div class="col-12">
      <div class="flex flex-column gap-1">
        <label for="name">{{ $t(`table.instructors.name`) }}</label>
        <InputText v-model="instance.name"
                   maxlength="60"
                   pattern="A:[A-ž\s\-]:multiple"
                   :invalid="errorList.name"
                   id="name"/>
        <InputError :errors="errorList.name" />
      </div>
    </div>
  </form>
</AdminForm>
</template>

<script setup>
import {computed, onBeforeMount, ref} from "vue";
import { useFetchDataStore } from "@/stores/fetchDataStore";
import { useErrorStore } from "@/stores/errorStore";

import AdminForm from "@/components/AdminForm.vue";
import InputError from "@/components/error/inputError.vue";
import axios from "@/services/axios";

const fetchDataStore = useFetchDataStore();
let instance = computed(() => fetchDataStore.instance);
const errorStore = useErrorStore();

let permissions = ref([]);

let errorList = computed(() => errorStore.errorList);

onBeforeMount(async () => {
  const { data } =  await axios.get(`/permissions`);
  const permissions = data.instances.map(permission => ({
    ...permission, checked: false
  }));


  fetchDataStore.setProperty(`permissions`, permissions);
});
</script>

<style scoped>

</style>