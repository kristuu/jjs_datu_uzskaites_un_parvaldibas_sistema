<template>
  <AdminForm
    v-if="can('edit instances') && can('manage event_categories')"
    :database-table="`event_categories`"
    :model-name="`EventCategory`"
    :page-name="$t(`pageHeadings.event_categories.event_categories`)"
    :short-desc="$t(`pageHeadings.event_categories.edit event_category`)"
  >
    <form class="row gap-3 py-3 text-start needs-validation">
      <div class="col-12">
        <div class="flex flex-column gap-1">
          <label for="name">{{ $t(`fields.event_categories.name`) }}</label>
          <InputText
            id="name"
            v-model="instance.name"
            :disabled="fetchDataStore.isFetching || fetchDataStore.isProcessing"
            :invalid="errorList.name"
          />
          <InputError :errors="errorList.name" />
        </div>
      </div>
    </form>
  </AdminForm>
</template>

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

<style scoped></style>
