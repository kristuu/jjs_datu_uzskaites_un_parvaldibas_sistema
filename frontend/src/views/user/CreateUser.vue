<script setup>
import {ref, computed} from "vue";
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
<AdminForm :page-name="$t(`pageHeadings.users.users`)"
           :short-desc="$t(`pageHeadings.users.create user`)"
           :model-name="`User`"
           :database-table="`users`"
           @update-error-list="handleErrorListUpdate">
  <form id="createUserForm" class="row gap-3 py-3 text-start needs-validation">
    <div class="col-12">
      <div class="form-group form-floating required">
        <input v-model="formInstance.name"
               type="text"
               maxlength="60"
               v-maska data-maska="A A" data-maska-tokens="A:[A-ž\s\-]:multiple"
               class="form-control"
               :class="{ 'is-invalid' : errorList.name }"
               id="name"
               placeholder="Vārds (-i)">
        <label for="name">Vārds (-i)</label>
        <InputError :errors="errorList.name" />
      </div>
    </div>
    <div class="col-12">
      <div class="form-group form-floating required">
        <input v-model="formInstance.surname"
               type="text"
               maxlength="60"
               v-maska data-maska="A A" data-maska-tokens="A:[A-ž]:multiple"
               class="form-control"
               :class="{ 'is-invalid' : errorList.surname?.length > 0 }"
               id="surname"
               placeholder="Uzvārds (-i)">
        <label for="surname">Uzvārds (-i)</label>
        <InputError :errors="errorList.surname" />
      </div>
    </div>
    <div class="col-12">
      <div class="form-group form-floating required">
        <input v-model="formInstance.masked_person_code"
               inputmode="numeric" type="text"
               v-maska data-maska-eager data-maska="######-#####"
               @maska="caller => formInstance.person_code = caller.detail.unmasked"
               class="form-control"
               :class="{ 'is-invalid' : errorList.person_code?.length > 0 }"
               id="person_code"
               placeholder="Personas kods">
        <label for="person_code">Personas kods</label>
        <InputError :errors="errorList.person_code" />
      </div>
    </div>
    <div class="col-12">
      <VueDatePicker v-model="formInstance.birthdate"
                     locale="lv"
                     position="left"
                     cancelText="Atcelt"
                     selectText="Saglabāt"
                     :enable-time-picker="false"
                     :format="'dd.MM.yyyy'"
                     auto-apply
                     @update:modelValue="val => formInstance.birthdate = format(new Date(val), 'yyyy-MM-dd HH:mm:ss')">
        <template #dp-input="{ value }">
          <div class="form-group form-floating required">
            <input id="birthdate"
                   type="text"
                   :value="value"
                   class="form-control"
                   :class="{ 'is-invalid' : errorList.birthdate?.length > 0 }"
                   placeholder="Dzimšanas datums"
                   autocomplete="off" readonly />
            <label for="birthdate">Dzimšanas datums</label>
            <InputError :errors="errorList.birthdate" />
          </div>
        </template>
      </VueDatePicker>
    </div>
    <div class="col-12">
      <div class="form-group form-floating required">
        <input v-model="formInstance.email"
               inputmode="email" type="email"
               class="form-control"
               :class="{ 'is-invalid' : errorList.email?.length > 0 }"
               id="email"
               placeholder="E-pasta adrese">
        <label for="email">E-pasta adrese</label>
        <InputError :errors="errorList.email" />
      </div>
    </div>
    <div class="col-12">
      <div class="form-group form-floating required">
        <vue-tel-input v-model="formInstance.phone"
                       class="form-control"
                       :class="{ 'is-invalid' : errorList.phone?.length > 0 }"
                       :auto-format="true"
                       autocomplete="off"
                       mode="international">
          <template #input="{ props, actions, value, update }">
            <input ref="phone" v-on="{ ...actions }" v-bind="props"
                   :value="value" @input="update($event.target.value)"
                   class="form-control" placeholder=" "/>
          </template>
        </vue-tel-input>
        <label for="phone">Telefona nr.</label>
        <InputError :errors="errorList.phone" />
      </div>
    </div>
    <div class="col-12">
      <div class="form-group form-floating">
        <input v-model="formInstance.iban_code"
               type="text"
               class="form-control"
               :class="{ 'is-invalid' : errorList.iban_code?.length > 0 }"
               id="iban_code"
               placeholder="IBAN (starptautiskais bankas konta numurs)">
        <label for="iban_code">IBAN (starptautiskais bankas konta numurs)</label>
        <InputError :errors="errorList.iban_code" />
      </div>
    </div>
    <div class="col-12">
      <div class="form-group form-floating required">
        <input v-model="formInstance.password"
               type="password"
               class="form-control"
               :class="{ 'is-invalid' : errorList.password?.length > 0 }"
               id="password"
               placeholder="Parole">
        <label for="password">Parole</label>
        <InputError :errors="errorList.password" />
      </div>
    </div>
    <div class="col-12">
      <div class="form-group form-floating required">
        <input v-model="formInstance.password_confirmation"
               type="password"
               class="form-control"
               :class="{ 'is-invalid' : errorList.password_confirmation?.length > 0 }"
               id="password_confirmation"
               placeholder="Parole (atkārtoti)">
        <label for="password_confirmation">Parole (atkārtoti)</label>
        <InputError :errors="errorList.password_confirmation" />
      </div>
    </div>
  </form>
</AdminForm>
</template>

<style scoped>

</style>