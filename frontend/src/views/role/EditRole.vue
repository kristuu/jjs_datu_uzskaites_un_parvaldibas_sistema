<script setup>
import {capitalize, computed, ref} from "vue";
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
  <AdminForm v-if="can('edit instances') && can('manage roles')"
             :page-name="$t(`pageHeadings.roles.roles`)"
             :short-desc="$t(`pageHeadings.roles.edit role`)"
             :model-name="`Role`"
             :database-table="`roles`"
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
      <div class="col-12">
        <div class="flex flex-column gap-1">
          <label for="name">Tiesības</label>
          <div class="row">
            <div class="col-12 col-sm-6 d-flex align-items-center" v-for="(permission, index) in formInstance.permissions" :key="index">
              <InputSwitch v-model="formInstance.permissions[index].checked"
                           :invalid="errorList.name"
                           @update:modelValue="console.log(formInstance.permissions)"/>
              <label class="ms-2">{{ capitalize(permission.name) }}</label>
            </div>
          </div>
          <InputError :errors="errorList.name" />
        </div>
      </div>
    </form>
  </AdminForm>
</template>

<style scoped>
</style>