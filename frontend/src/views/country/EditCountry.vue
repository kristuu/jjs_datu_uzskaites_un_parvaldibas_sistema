<script setup>
import {computed, ref} from "vue";
import { useFetchDataStore } from "@/stores/fetchDataStore";
import { useErrorStore } from "@/stores/errorStore";

import AdminForm from "@/components/AdminForm.vue";
import InputError from "@/components/error/inputError.vue";

const fetchDataStore = useFetchDataStore();
const errorStore = useErrorStore();

let instance = computed(() => fetchDataStore.instance);
let errorList = computed(() => errorStore.errorList);
</script>

<template>
  <AdminForm v-if="can('edit instances') && can('manage countries')"
             :page-name="$t(`pageHeadings.countries.countries`)"
             :short-desc="$t(`pageHeadings.countries.edit country`)"
             :model-name="`Country`"
             :database-table="`countries`"
             @update-error-list="handleErrorListUpdate">
    <form id="editUserForm" class="row gap-3 py-3 text-start needs-validation">
      <div class="col-12">
        <div class="flex flex-column gap-1">
          <label for="name">Nosaukums</label>
          <InputText v-model="instance.name"
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