<script setup>
import {ref, computed, onBeforeMount} from "vue";
import { useFetchDataStore } from "@/stores/fetchDataStore";
import { useErrorStore } from "@/stores/errorStore";

import AdminForm from "@/components/AdminForm.vue";
import InputError from "@/components/error/inputError.vue";

const fetchDataStore = useFetchDataStore();
const errorStore = useErrorStore();

let instance = computed(() => fetchDataStore.instance);
let errorList = computed(() => errorStore.errorList);

const handleErrorListUpdate = (updatedErrorList) => {
  errorList.value = updatedErrorList;
}
</script>

<template>
<AdminForm :page-name="$t(`pageHeadings.countries.countries`)"
           :short-desc="$t(`pageHeadings.countries.create country`)"
           :model-name="`Country`"
           :database-table="`countries`"
           @update-error-list="handleErrorListUpdate">
  <form id="createUserForm" class="row gap-3 py-3 text-start needs-validation">
    <div class="col-12">
      <div class="flex flex-column gap-1">
        <label for="name">Nosaukums</label>
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

<style scoped>

</style>