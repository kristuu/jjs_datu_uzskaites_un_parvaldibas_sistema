<template>
<AdminForm v-if="can('manage roles')"
           :page-name="$t(`pageHeadings.roles.roles`)"
           :short-desc="$t(`pageHeadings.roles.create role`)"
           :model-name="`Role`"
           :database-table="`roles`">
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
      <div class="flex flex-column gap-1">
        <label for="name">Tiesības</label>
        <div class="row">
          <div class="col-12 col-sm-6 d-flex align-items-center" v-for="(permission, index) in instance.permissions" :key="permission.id">
            <InputSwitch v-model="instance.permissions[index].checked"
                         :invalid="errorList.permissions"
                         @update:modelValue="console.log(instance.permissions)"/>
            <label class="ms-2">{{ capitalize(permission.name) }}</label>
          </div>
        </div>
        <InputError :errors="errorList.permissions" />
      </div>
    </div>
  </form>
</AdminForm>
</template>

<script setup>
import {capitalize, computed, onBeforeMount, ref} from "vue";
import { useFetchDataStore } from "@/stores/fetchDataStore";
import { useErrorStore } from "@/stores/errorStore";

import AdminForm from "@/components/AdminForm.vue";
import InputError from "@/components/error/inputError.vue";
import axios from "@/services/axios";

const fetchDataStore = useFetchDataStore();
let instance = computed(() => fetchDataStore.instance);
const errorStore = useErrorStore();

let permissions = ref([]);

let errorList = computed(() => errorStore.errorList);

onBeforeMount(async () => {
  const { data } =  await axios.get(`/permissions`);
  const permissions = data.instances.map(permission => ({
    ...permission, checked: false
  }));


  fetchDataStore.setProperty(`permissions`, permissions);
});
</script>

<style scoped>

</style>