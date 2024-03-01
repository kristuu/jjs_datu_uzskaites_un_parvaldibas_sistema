<script setup>
import {computed, ref} from "vue";
import { useStore } from 'vuex';

import { format } from 'date-fns';
import { vMaska } from 'maska';

import AdminForm from "@/components/AdminForm.vue";
import InputError from "@/components/error/inputError.vue";
import Password from "primevue/password";

const store = useStore();
let formInstance = computed(() => store.state.formInstance);
let errorList = ref({});

const handleErrorListUpdate = (updatedErrorList) => {
  errorList.value = updatedErrorList;
}
</script>

<template>
  <AdminForm v-if="can('edit instances') && can('manage users')"
             :page-name="$t(`pageHeadings.users.users`)"
             :short-desc="$t(`pageHeadings.users.edit user`)"
             :model-name="`User`"
             :database-table="`users`"
             @update-error-list="handleErrorListUpdate">
    <form id="editUserForm" class="row gap-3 py-3 text-start needs-validation">
      <div class="col-12">
        <div class="flex flex-column gap-1">
          <label for="name">Vārds (-i)</label>
          <InputText v-model="formInstance.name"
                     maxlength="60"
                     pattern="A:[A-ž\s\-]:multiple"
                     :invalid="errorList.name"
                     id="name"/>
          <InputError :errors="errorList.name" />
        </div>
      </div>
      <div class="col-12">
        <div class="flex flex-column gap-1">
          <label for="surname">Uzvārds (-i)</label>
          <InputText v-model="formInstance.surname"
                     maxlength="60"
                     v-maska data-maska="A A" data-maska-tokens="A:[A-ž\s\-]:multiple"
                     :invalid="errorList.surname"
                     id="surname"/>
          <InputError :errors="errorList.surname" />
        </div>
      </div>
      <div class="col-12">
        <div class="flex flex-column gap-1">
          <label for="person_code">Personas kods</label>
          <InputMask v-model="formInstance.person_code"
                     mask="999999-99999"
                     :invalid="errorList.person_code"
                     id="person_code"
                     unmask />
          <InputError :errors="errorList.person_code" />
        </div>
      </div>
      <div class="col-12">
        <div class="flex flex-column gap-1">
          <label for="birthdate">Dzimšanas datums</label>
          <Calendar v-model="formInstance.birthdate"
                    :invalid="errorList.birthdate"
                    id="birthdate"
                    dateFormat="dd.mm.yy"
                    :maxDate="new Date()"
                    panelClass="min-w-min"/>
          <InputError :errors="errorList.birthdate" />
        </div>
      </div>
      <div class="col-12">
        <div class="flex flex-column gap-1">
          <label for="email">E-pasts</label>
          <InputText v-model="formInstance.email"
                     :invalid="errorList.email"
                     id="email"/>
          <InputError :errors="errorList.email" />
        </div>
      </div>
      <div class="col-12">
        <div class="flex flex-column gap-1">
          <label for="phone">Telefona nr.</label>
          <vue-tel-input v-model="formInstance.phone"
                         :auto-format="true"
                         autocomplete="off"
                         mode="international">
            <template #input="{ props, actions, value, update }">
              <InputText ref="phone" v-on="{ ...actions }" v-bind="props"
                         :value="value" @input="update($event.target.value)"
                         :invalid="errorList.phone?.length > 0"/>
            </template>
          </vue-tel-input>
          <InputError :errors="errorList.phone" />
        </div>
      </div>
      <div class="col-12">
        <div class="flex flex-column gap-1">
          <label for="iban_code">Bankas konts</label>
          <InputMask v-model="formInstance.iban_code"
                     :invalid="errorList.iban_code?.length > 0"
                     id="iban_code"
                     mask="aa*************?******************"
                     placeholder="Bankas konts" />
          <InputError :errors="errorList.iban_code" />
        </div>
      </div>
      <div class="col-12">
        <div class="flex flex-column gap-1">
          <label for="password">Parole</label>
          <Password v-model="formInstance.password"
                    :invalid="errorList.password?.length > 0"
                    id="password"
                    inputStyle="width: 100%" toggleMask />
          <InputError :errors="errorList.password" />
        </div>
      </div>
      <div class="col-12">
        <div class="flex flex-column gap-1">
          <label for="password_confirmation">Paroles apstiprinājums</label>
          <Password v-model="formInstance.password_confirmation"
                    :invalid="errorList.password_confirmation?.length > 0"
                    id="password_confirmation"
                    inputStyle="width: 100%" toggleMask />
          <InputError :errors="errorList.password_confirmation" />
        </div>
      </div>
      <div class="col-12">
        <label for="roles">Lomas</label>
        <div class="row">
          <div class="col-12 col-md-4 d-flex align-items-center" v-for="(role, index) in formInstance.all_roles" :key="index">
            <ToggleButton v-model="role.userHas" class="w-full h-full"
                         :invalid="errorList.name"
                          :off-label="role.name" :on-label="role.name"
                         @update:modelValue="console.log(formInstance.roles)"/>
          </div>
        </div>
      </div>
    </form>
  </AdminForm>
</template>

<style scoped>
</style>