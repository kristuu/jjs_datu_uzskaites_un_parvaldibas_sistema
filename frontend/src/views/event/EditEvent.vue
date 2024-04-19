<script setup>
import { computed } from "vue";
import { useFetchDataStore } from "@/stores/fetchDataStore";
import { useErrorStore } from "@/stores/errorStore";

import AdminForm from "@/components/AdminForm.vue";
import InputError from "@/components/error/inputError.vue";

const fetchDataStore = useFetchDataStore();
let instance = computed(() => fetchDataStore.instance);
const errorStore = useErrorStore();

let errorList = computed(() => errorStore.errorList);
</script>

<template>
  <AdminForm
    v-if="can('edit instances') && can('manage permissions')"
    :database-table="`events`"
    :model-name="`Event`"
    :page-name="$t(`pageHeadings.events.events`)"
    :short-desc="$t(`pageHeadings.events.edit event`)"
  >
    <form id="editUserForm" class="row gap-3 py-3 text-start needs-validation">
      <div class="col-12">
        <div class="flex flex-column gap-1">
          <label for="name">Nosaukums</label>
          <InputText
            id="name"
            v-model="instance.name"
            :invalid="errorList.name"
          />
          <InputError :errors="errorList.name" />
        </div>
      </div>
      <div class="col-12 sm:col-6">
        <div class="flex flex-column gap-1">
          <label for="start">Sākums</label>
          <Calendar
            id="start"
            v-model="instance.start"
            :invalid="errorList.start"
          />
          <InputError :errors="errorList.start" />
        </div>
      </div>
      <div class="col-12 sm:col-6">
        <div class="flex flex-column gap-1">
          <label for="end">Beigas</label>
          <Calendar id="end" v-model="instance.end" :invalid="errorList.end" />
          <InputError :errors="errorList.end" />
        </div>
      </div>
    </form>
  </AdminForm>
</template>

<style scoped></style>
