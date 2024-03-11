<script setup>
import {ref, computed} from "vue";
import { useFetchDataStore } from "@/stores/fetchDataStore";
import { useErrorStore } from "@/stores/errorStore";
import axios from '@/services/axios';

import AdminForm from "@/components/AdminForm.vue";
import InputError from "@/components/error/inputError.vue";

const fetchDataStore = useFetchDataStore();
let instance = computed(() => fetchDataStore.instance);
const errorStore = useErrorStore();

let selectedCountry = ref();
let countryList = ref([]);
axios.get(`/countries`)
    .then(response => {
       countryList.value = response.data.instances;
    })
    .catch(e => {
      console.error(e);
    })

let errorList = computed(() => errorStore.errorList);
</script>

<template>
<AdminForm :page-name="$t(`pageHeadings.regions.regions`)"
           :short-desc="$t(`pageHeadings.regions.create region`)"
           :model-name="`Region`"
           :database-table="`regions`">
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
    <div class="col-12">
      <div class="d-flex flex-column gap-1">
        <label for="country">Piederīgā valsts</label>
        <Dropdown v-model="selectedCountry"
                  @update:modelValue="instance.country_id = selectedCountry.id"
                  :options="countryList"
                  optionLabel="name"
                  :invalid="errorList.country_id"
                  id="country" filter/>
        <InputError :errors="errorList.country_id" />
      </div>
    </div>
  </form>
</AdminForm>
</template>

<style scoped>

</style>