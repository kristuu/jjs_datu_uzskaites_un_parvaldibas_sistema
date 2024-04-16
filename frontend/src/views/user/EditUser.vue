<script setup>
import {computed} from "vue";
import { useFetchDataStore } from "@/stores/fetchDataStore";
import { useErrorStore } from "@/stores/errorStore";

import AdminForm from "@/components/AdminForm.vue";
import InputError from "@/components/error/inputError.vue";

const fetchDataStore = useFetchDataStore();
let instance = computed(() => fetchDataStore.instance);
const errorStore = useErrorStore();

let errorList = computed(() => errorStore.errorList);
</script>

<template>
  <AdminForm v-if="can('edit instances') && can('manage users')"
             :page-name="$t(`pageHeadings.users.users`)"
             :short-desc="$t(`pageHeadings.users.edit user`)"
             :model-name="`User`"
             :database-table="`users`">
    <form id="editUserForm" class="row gap-3 py-3 text-start needs-validation">
      <div class="col-12">
        <div class="flex flex-column gap-1">
          <label for="name">{{ $t(`fields.user.name`) }}</label>
          <InputText v-model="instance.name"
                     maxlength="60"
                     pattern="A:[A-ž\s\-]:multiple"
                     :invalid="errorList.name"
                     :disabled="fetchDataStore.isFetching || fetchDataStore.isProcessing"
                     id="name"/>
          <InputError :errors="errorList.name" />
        </div>
      </div>
      <div class="col-12">
        <div class="flex flex-column gap-1">
          <label for="surname">{{ $t(`fields.user.surname`) }}</label>
          <InputText v-model="instance.surname"
                     maxlength="60"
                     :invalid="errorList.surname"
                     :disabled="fetchDataStore.isFetching || fetchDataStore.isProcessing"
                     id="surname"/>
          <InputError :errors="errorList.surname" />
        </div>
      </div>
      <div class="col-12">
        <div class="flex flex-column gap-1">
          <label for="person_code">{{ $t(`fields.user.person_code`) }}</label>
          <InputMask v-model="instance.person_code"
                     mask="999999-99999"
                     :invalid="errorList.person_code"
                     :disabled="fetchDataStore.isFetching || fetchDataStore.isProcessing"
                     id="person_code"
                     unmask />
          <InputError :errors="errorList.person_code" />
        </div>
      </div>
      <div class="col-12">
        <div class="flex flex-column gap-1">
          <label for="birthdate">{{ $t(`fields.user.birthdate`) }}</label>
          <Calendar v-model="instance.birthdate"
                    :invalid="errorList.birthdate"
                    :disabled="fetchDataStore.isFetching || fetchDataStore.isProcessing"
                    id="birthdate"
                    dateFormat="dd.mm.yy"
                    :maxDate="new Date()"
                    panelClass="min-w-min"/>
          <InputError :errors="errorList.birthdate" />
        </div>
      </div>
      <div class="col-12">
        <div class="flex flex-column gap-1">
          <label for="email">{{ $t(`fields.user.email`) }}</label>
          <InputText v-model="instance.email"
                     :invalid="errorList.email"
                     :disabled="fetchDataStore.isFetching || fetchDataStore.isProcessing"
                     id="email"/>
          <InputError :errors="errorList.email" />
        </div>
      </div>
      <div class="col-12">
        <div class="flex flex-column gap-1">
          <label for="phone">{{ $t(`fields.user.phone`) }}</label>
          <vue-tel-input v-model="instance.phone"
                         :auto-format="true"
                         autocomplete="off"
                         mode="international">
            <template #input="{ props, actions, value, update }">
              <InputText ref="phone" v-on="{ ...actions }" v-bind="props"
                         :value="value" @input="update($event.target.value)"
                         :invalid="errorList.phone?.length > 0"
                         :disabled="fetchDataStore.isFetching || fetchDataStore.isProcessing" />
            </template>
          </vue-tel-input>
          <InputError :errors="errorList.phone" />
        </div>
      </div>
      <div class="col-12">
        <div class="flex flex-column gap-1">
          <label for="iban_code">{{ $t(`fields.user.iban_code`) }}</label>
          <InputMask v-model="instance.iban_code"
                     :invalid="errorList.iban_code?.length > 0"
                     :disabled="fetchDataStore.isFetching || fetchDataStore.isProcessing"
                     id="iban_code"
                     mask="aa*************?******************" />
          <InputError :errors="errorList.iban_code" />
        </div>
      </div>
      <div class="col-12">
        <div class="flex flex-column gap-1">
          <label for="password">{{ $t(`fields.user.password`) }}</label>
          <Password v-model="instance.password"
                    :invalid="errorList.password?.length > 0"
                    :disabled="fetchDataStore.isFetching || fetchDataStore.isProcessing"
                    id="password"
                    inputStyle="width: 100%" toggleMask />
          <InputError :errors="errorList.password" />
        </div>
      </div>
      <div class="col-12">
        <div class="flex flex-column gap-1">
          <label for="password_confirmation">{{ $t(`fields.user.password_confirmation`) }}</label>
          <Password v-model="instance.password_confirmation"
                    :invalid="errorList.password_confirmation?.length > 0"
                    :disabled="fetchDataStore.isFetching || fetchDataStore.isProcessing"
                    id="password_confirmation"
                    inputStyle="width: 100%" toggleMask />
          <InputError :errors="errorList.password_confirmation" />
        </div>
      </div>
      <div class="col-12">
        <label for="roles">{{ $t(`fields.user.roles`) }}</label>
        <div class="row">
          <div class="col-12 col-md-4 d-flex align-items-center" v-for="(role, index) in instance.all_roles" :key="index">
            <ToggleButton v-model="role.userHas" class="w-full h-full"
                          :invalid="errorList.role?.name"
                          :disabled="fetchDataStore.isFetching || fetchDataStore.isProcessing"
                          :off-label="role.name" :on-label="role.name"
                          @update:modelValue="console.log(instance.roles)"/>
          </div>
        </div>
      </div>
    </form>
  </AdminForm>
</template>

<style scoped>
</style>