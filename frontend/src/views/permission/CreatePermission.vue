<script setup>
import {ref, computed} from "vue";
import { useStore } from 'vuex';

import AdminForm from "@/components/AdminForm.vue";
import InputError from "@/components/error/inputError.vue";

const store = useStore();
let formInstance = computed(() => store.state.formInstance);
let errorList = ref({});

const handleErrorListUpdate = (updatedErrorList) => {
  errorList.value = updatedErrorList;
}

console.log(store);
</script>

<template>
<AdminForm :page-name="$t(`pageHeadings.permissions.permissions`)"
           :short-desc="$t(`pageHeadings.permissions.create permission`)"
           :model-name="`Permission`"
           :database-table="`permissions`"
           @update-error-list="handleErrorListUpdate">
  <form class="row gap-3 py-3 text-start needs-validation">
    <div class="col-12">
      <div class="flex flex-column gap-1">
        <label for="name">Nosaukums</label>
        <InputText v-model="formInstance.name"
                   maxlength="60"
                   pattern="A:[A-ž\s\-]:multiple"
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