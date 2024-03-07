<script setup>
import {ref, computed} from "vue";
import { useStore } from 'vuex';

import Password from 'primevue/password';


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
<AdminForm v-if="can('manage roles')"
           :page-name="$t(`pageHeadings.roles.roles`)"
           :short-desc="$t(`pageHeadings.roles.create role`)"
           :model-name="`Role`"
           :database-table="`roles`"
           @update-error-list="handleErrorListUpdate">
  <form id="createUserForm" class="row gap-3 py-3 text-start needs-validation">
    <div class="col-12">
      <div class="flex flex-column gap-1">
        <label for="name">Nosaukums</label>
        <InputText v-model="formInstance.name"
                   maxlength="60"
                   pattern="A:[A-ž\s\-]:multiple"
                   :invalid="errorList.name"
                   id="name"/>
        {{ errorList }}
        <InputError :errors="errorList" />
      </div>
    </div>
  </form>
</AdminForm>
</template>

<style scoped>

</style>