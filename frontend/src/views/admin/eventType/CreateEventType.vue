<template>
  <AdminForm
    :database-table="`event_types`"
    :model-name="`EventType`"
    :page-name="$t(`pageHeadings.event_types.event_types`)"
    :short-desc="$t(`pageHeadings.event_types.create event_type`)"
  >
    <form class="row gap-3 py-3 text-start needs-validation">
      <div class="col-12">
        <div class="flex flex-column gap-1">
          <label for="name">{{ $t(`fields.event_types.name`) }}</label>
          <InputText
            id="name"
            v-model="instance.name"
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
