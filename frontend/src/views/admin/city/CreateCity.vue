<script setup>
import { computed, ref } from "vue";
import { useFetchDataStore } from "@/stores/fetchDataStore";
import { useErrorStore } from "@/stores/errorStore";
import axios from "@/services/axios";

import AdminForm from "@/components/AdminForm.vue";
import InputError from "@/components/error/inputError.vue";

const fetchDataStore = useFetchDataStore();
let instance = computed(() => fetchDataStore.instance);
const errorStore = useErrorStore();

let selectedCountry = ref();
let countryList = ref([]);
axios
  .get(`/api/countries`)
  .then((response) => {
    countryList.value = response.data.instances;
  })
  .catch((e) => {
    console.error(e);
  });

let errorList = computed(() => errorStore.errorList);
</script>

<template>
  <AdminForm
    :database-table="`cities`"
    :model-name="`City`"
    :page-name="$t(`pageHeadings.cities.cities`)"
    :short-desc="$t(`pageHeadings.cities.create city`)"
  >
    <form
      id="createUserForm"
      class="row gap-3 py-3 text-start needs-validation"
    >
      <div class="col-12">
        <div class="flex flex-column gap-1">
          <label for="name">Nosaukums</label>
          <InputText
            id="name"
            v-model="instance.name"
            :invalid="errorList.name"
            maxlength="60"
            pattern="A:[A-ž\s\-]:multiple"
          />
          <InputError :errors="errorList.name" />
        </div>
      </div>
      <div class="col-12">
        <div class="d-flex flex-column gap-1">
          <label for="country">Piederīgā valsts</label>
          <Dropdown
            id="country"
            v-model="selectedCountry"
            :invalid="errorList.country_id"
            :options="countryList"
            filter
            optionLabel="name"
            @update:modelValue="instance.country_id = selectedCountry.id"
          />
          <InputError :errors="errorList.country_id" />
        </div>
      </div>
    </form>
  </AdminForm>
</template>

<style scoped></style>
