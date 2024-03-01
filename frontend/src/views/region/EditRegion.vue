<script setup>
import {computed, ref} from "vue";
import { useStore } from 'vuex';

import { format } from 'date-fns';
import { vMaska } from 'maska';

import AdminForm from "@/components/AdminForm.vue";
import InputError from "@/components/error/inputError.vue";

const store = useStore();
let formInstance = computed(() => store.state.formInstance);
let errorList = ref({});

const handleErrorListUpdate = (updatedErrorList) => {
  errorList.value = updatedErrorList;
}
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
          <InputText v-model="formInstance.name"
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