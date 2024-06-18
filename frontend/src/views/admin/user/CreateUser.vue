<script setup>
import { computed } from "vue";
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
  <AdminForm
    :database-table="`users`"
    :model-name="`User`"
    :page-name="$t(`pageHeadings.users.users`)"
    :short-desc="$t(`pageHeadings.users.create user`)"
    @update-error-list="handleErrorListUpdate"
  >
    <form
      id="createUserForm"
      class="row gap-3 py-3 text-start needs-validation"
    >
      <div class="col-12">
        <div class="flex flex-column gap-1">
          <label for="name">{{ $t(`table.users.name`) }}</label>
          <InputText
            id="name"
            v-model="instance.name"
            :invalid="errorList.name"
            maxlength="60"
            pattern="A:[A-ž\s\-]:multiple"
          />
          <InputError :errors="errorList.name" />
        </div>
      </div>
      <div class="col-12">
        <div class="flex flex-column gap-1">
          <label for="surname">{{ $t(`table.users.surname`) }}</label>
          <InputText
            id="surname"
            v-model="instance.surname"
            v-maska
            :invalid="errorList.surname"
            data-maska="A A"
            data-maska-tokens="A:[A-ž\s\-]:multiple"
            maxlength="60"
          />
          <InputError :errors="errorList.surname" />
        </div>
      </div>
      <div class="col-12">
        <div class="flex flex-column gap-1">
          <label for="person_code">{{ $t(`table.users.person_code`) }}</label>
          <InputMask
            id="person_code"
            v-model="instance.person_code"
            :invalid="errorList.person_code"
            mask="999999-99999"
            unmask
          />
          <InputError :errors="errorList.person_code" />
        </div>
      </div>
      <div class="col-12">
        <div class="flex flex-column gap-1">
          <label for="birthdate">{{ $t(`table.users.birthdate`) }}</label>
          <Calendar
            id="birthdate"
            v-model="instance.birthdate"
            :invalid="errorList.birthdate"
            :maxDate="new Date()"
            dateFormat="dd.mm.yy"
            panelClass="min-w-min"
          />
          <InputError :errors="errorList.birthdate" />
        </div>
      </div>
      <div class="col-12">
        <div class="flex flex-column gap-1">
          <label for="email">{{ $t(`table.users.email`) }}</label>
          <InputText
            id="email"
            v-model="instance.email"
            :invalid="errorList.email"
          />
          <InputError :errors="errorList.email" />
        </div>
      </div>
      <div class="col-12">
        <div class="flex flex-column gap-1">
          <label for="phone">{{ $t(`table.users.phone`) }}</label>
          <vue-tel-input
            v-model="instance.phone"
            :auto-format="true"
            autocomplete="off"
            mode="international"
          >
            <template #input="{ props, actions, value, update }">
              <InputText
                ref="phone"
                :invalid="errorList.phone?.length > 0"
                :value="value"
                v-bind="props"
                @input="update($event.target.value)"
                v-on="{ ...actions }"
              />
            </template>
          </vue-tel-input>
          <InputError :errors="errorList.phone" />
        </div>
      </div>
      <div class="col-12">
        <div class="flex flex-column gap-1">
          <label for="iban_code">{{ $t(`fields.user.iban_code`) }}</label>
          <InputMask
            id="iban_code"
            v-model="instance.iban_code"
            :invalid="errorList.iban_code?.length > 0"
            mask="aa*************?******************"
            placeholder="Bankas konts"
          />
          <InputError :errors="errorList.iban_code" />
        </div>
      </div>
      <div class="col-12">
        <div class="flex flex-column gap-1">
          <label for="password">Parole</label>
          <Password
            id="password"
            v-model="instance.password"
            :invalid="errorList.password?.length > 0"
            inputStyle="width: 100%"
            toggleMask
          />
          <InputError :errors="errorList.password" />
        </div>
      </div>
      <div class="col-12">
        <div class="flex flex-column gap-1">
          <label for="password_confirmation">Paroles apstiprinājums</label>
          <Password
            id="password_confirmation"
            v-model="instance.password_confirmation"
            :invalid="errorList.password_confirmation?.length > 0"
            inputStyle="width: 100%"
            toggleMask
          />
          <InputError :errors="errorList.password_confirmation" />
        </div>
      </div>
    </form>
  </AdminForm>
</template>

<style scoped></style>
