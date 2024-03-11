<script setup>
import {computed, ref, watch} from "vue";
import {useFetchDataStore} from "@/stores/fetchDataStore";
import {useErrorStore} from "@/stores/errorStore";
import axios from '@/services/axios';

import AdminForm from "@/components/AdminForm.vue";
import InputError from "@/components/error/inputError.vue";

const fetchDataStore = useFetchDataStore();
let instance = computed(() => fetchDataStore.instance);
const errorStore = useErrorStore();

let selectedCountry = ref(instance.country);
let countryList = ref([]);
axios.get(`/countries`)
    .then(response => {
      countryList.value = response.data.instances;
    })
    .catch(e => {
      console.error(e);
    })

let errorList = computed(() => errorStore.errorList);

watch(instance, (newValue) => {
  if (newValue) {
    selectedCountry.value = newValue.country;
  }
});
</script>

<template>
  <AdminForm v-if="can('edit instances') && can('manage regions')"
             :page-name="$t(`pageHeadings.regions.regions`)"
             :short-desc="$t(`pageHeadings.regions.edit region`)"
             :model-name="`Region`"
             :database-table="`regions`"
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