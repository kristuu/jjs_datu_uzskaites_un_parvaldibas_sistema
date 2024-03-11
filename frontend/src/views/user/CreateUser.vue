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
<AdminForm :page-name="$t(`pageHeadings.users.users`)"
           :short-desc="$t(`pageHeadings.users.create user`)"
           :model-name="`User`"
           :database-table="`users`"
           @update-error-list="handleErrorListUpdate">
  <form id="createUserForm" class="row gap-3 py-3 text-start needs-validation">
    <div class="col-12">
      <div class="flex flex-column gap-1">
        <label for="name">Vārds (-i)</label>
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
        <label for="surname">Uzvārds (-i)</label>
        <InputText v-model="instance.surname"
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
        <InputMask v-model="instance.person_code"
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
        <Calendar v-model="instance.birthdate"
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
        <InputText v-model="instance.email"
                  :invalid="errorList.email"
                  id="email"/>
        <InputError :errors="errorList.email" />
      </div>
    </div>
    <div class="col-12">
      <div class="flex flex-column gap-1">
        <label for="phone">Telefona nr.</label>
        <vue-tel-input v-model="instance.phone"
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
        <InputMask v-model="instance.iban_code"
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
        <Password v-model="instance.password"
               :invalid="errorList.password?.length > 0"
               id="password"
               inputStyle="width: 100%" toggleMask />
        <InputError :errors="errorList.password" />
      </div>
    </div>
    <div class="col-12">
      <div class="flex flex-column gap-1">
        <label for="password_confirmation">Paroles apstiprinājums</label>
        <Password v-model="instance.password_confirmation"
                  :invalid="errorList.password_confirmation?.length > 0"
                  id="password_confirmation"
                  inputStyle="width: 100%" toggleMask />
        <InputError :errors="errorList.password_confirmation" />
      </div>
    </div>
  </form>
</AdminForm>
</template>

<style scoped>

</style>