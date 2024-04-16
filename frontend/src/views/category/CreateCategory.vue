<template>
<AdminForm :page-name="$t(`pageHeadings.categories.categories`)"
           :short-desc="$t(`pageHeadings.categories.create category`)"
           :model-name="`Category`"
           :database-table="`categories`">
  <form class="row gap-3 py-3 text-start needs-validation">
    <div class="col-12">
      <div class="flex flex-column gap-1">
        <label for="name">{{ $t(`fields.category.name`) }}</label>
        <InputText v-model="instance.name"
                   :invalid="errorList.name"
                   id="name"/>
        <InputError :errors="errorList.name" />
      </div>
    </div>
  </form>
</AdminForm>
</template>

<script setup>
import {computed, ref} from "vue";
import { useFetchDataStore } from "@/stores/fetchDataStore";
import { useErrorStore } from "@/stores/errorStore";

import AdminForm from "@/components/AdminForm.vue";
import InputError from "@/components/error/inputError.vue";

const fetchDataStore = useFetchDataStore();
let instance = computed(() => fetchDataStore.instance);
const errorStore = useErrorStore();

let errorList = computed(() => errorStore.errorList);
</script>

<style scoped>

</style>