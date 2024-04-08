<script setup>
import {capitalize, computed} from "vue";
import { useFetchDataStore } from "@/stores/fetchDataStore";
import { useErrorStore } from "@/stores/errorStore";

import AdminForm from "@/components/AdminForm.vue";
import InputError from "@/components/error/inputError.vue";

const fetchDataStore = useFetchDataStore();
let instance = computed(() => fetchDataStore.instance);
const errorStore = useErrorStore();

const getGeneralPermissions = (name) => {
  const permissions = [`create`, `edit`, `list`, `delete`];
  return permissions.some(permission => name.startsWith(permission));
}

const getManagePermissions = (name) => {
  const permissions = [`manage`];
  return permissions.some(permission => name.startsWith(permission));
}

const getOtherPermissions = (name) => {
  return !(getGeneralPermissions(name) || getManagePermissions(name));
}

let errorList = computed(() => errorStore.errorList);
</script>

<template>
  <AdminForm v-if="can('edit instances') && can('manage roles')"
             :page-name="$t(`pageHeadings.roles.roles`)"
             :short-desc="$t(`pageHeadings.roles.edit role`)"
             :model-name="`Role`"
             :database-table="`roles`">
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
        <div class="flex flex-column gap-1">
          <label for="name">Tiesības</label>
          <div class="row px-1">
            <div class="col-12">
              <Fieldset legend="General" toggleable>
                <div class="row mx-1">
                  <template v-for="(permission, index) in instance.permissions">
                    <div v-if="getGeneralPermissions(permission.name)" class="col-12 md:col-6 lg:col-4">
                      <InputSwitch v-model="instance.permissions[index].checked"
                                   :invalid="errorList.permissions"
                                   @update:modelValue="console.log(instance.permissions)"/>
                      <label class="ms-2">{{ capitalize(permission.name) }}</label>
                    </div>
                  </template>
                </div>
              </Fieldset>
              <Fieldset legend="Manage" toggleable>
                <div class="row mx-1">
                  <template v-for="(permission, index) in instance.permissions">
                    <div v-if="getManagePermissions(permission.name)" class="col-12 md:col-6 lg:col-4">
                      <InputSwitch v-model="instance.permissions[index].checked"
                                   :invalid="errorList.permissions"
                                   @update:modelValue="console.log(instance.permissions)"/>
                      <label class="ms-2">{{ capitalize(permission.name) }}</label>
                    </div>
                  </template>
                </div>
              </Fieldset>
              <Fieldset legend="Other" toggleable>
                <div class="row mx-1">
                  <template v-for="(permission, index) in instance.permissions">
                    <div v-if="getOtherPermissions(permission.name)" class="col-12 md:col-6 lg:col-4">
                      <InputSwitch v-model="instance.permissions[index].checked"
                                   :invalid="errorList.permissions"
                                   @update:modelValue="console.log(instance.permissions)"/>
                      <label class="ms-2">{{ capitalize(permission.name) }}</label>
                    </div>
                  </template>
                </div>
              </Fieldset>
            </div>
            <InputError :errors="errorList.permissions" />
          </div>
        </div>
      </div>
    </form>
  </AdminForm>
</template>

<style scoped>
</style>