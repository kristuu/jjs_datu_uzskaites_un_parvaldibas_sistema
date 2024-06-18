<script setup>
import { computed, ref } from "vue";
import axios from "@/services/axios";
import { useFetchDataStore } from "@/stores/fetchDataStore";
import { useErrorStore } from "@/stores/errorStore";

import AdminForm from "@/components/AdminForm.vue";
import InputError from "@/components/error/inputError.vue";

const fetchDataStore = useFetchDataStore();
let instance = computed(() => fetchDataStore.instance);
const errorStore = useErrorStore();

let errorList = computed(() => errorStore.errorList);

let categoryList = ref();

axios
  .get(`/api/certificates_categories`)
  .then((response) => {
    categoryList.value = response.data.instances;
    console.log(categoryList.value);
  })
  .catch((error) => {
    console.error(error);
  });

const displayCategory = (category) => `${category.name}`;
</script>

<template>
  <AdminForm
    :database-table="`certificates`"
    :model-name="`Certificate`"
    :page-name="$t(`pageHeadings.certificates.certificates`)"
    :short-desc="$t(`pageHeadings.certificates.edit certificate`)"
  >
    <form class="row gap-3 py-3 text-start needs-validation">
      <div class="col-12">
        <div class="flex flex-column gap-1">
          <label for="name">Kategorija</label>
          <Dropdown
            id="name"
            v-model="instance.category"
            :invalid="errorList.category_id"
            :optionLabel="displayCategory"
            :options="categoryList"
            filter
            resetFilterOnHide
            @update:modelValue="
              (value) => {
                instance.category_id = value.id;
              }
            "
          />
          <InputError :errors="errorList.category_id" />
        </div>
      </div>
      <div class="col-12">
        <div class="flex flex-column gap-1">
          <label for="name">Derīguma termiņš</label>
          <Calendar
            id="name"
            v-model="instance.expiration_date"
            :invalid="errorList.expiration_date"
          />
          <InputError :errors="errorList.expiration_date" />
        </div>
      </div>
    </form>
  </AdminForm>
</template>

<style scoped></style>
