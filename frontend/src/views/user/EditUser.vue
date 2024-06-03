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
    v-if="can('edit instances') && can('manage users')"
    :database-table="`users`"
    :model-name="`User`"
    :page-name="$t(`pageHeadings.users.users`)"
    :short-desc="$t(`pageHeadings.users.edit user`)"
  >
    <form id="editUserForm" class="row gap-3 py-3 text-start needs-validation">
      <div class="col-12">
        <div class="flex flex-column gap-1">
          <label for="name">{{ $t(`fields.user.name`) }}</label>
          <InputText
            id="name"
            v-model="instance.name"
            :disabled="fetchDataStore.isFetching || fetchDataStore.isProcessing"
            :invalid="errorList.name"
            d
            maxlength="60"
            pattern="A:[A-ž\s\-]:multiple"
          />
          <InputError :errors="errorList.name" />
        </div>
      </div>
      <div class="col-12">
        <div class="flex flex-column gap-1">
          <label for="surname">{{ $t(`fields.user.surname`) }}</label>
          <InputText
            id="surname"
            v-model="instance.surname"
            :disabled="fetchDataStore.isFetching || fetchDataStore.isProcessing"
            :invalid="errorList.surname"
            maxlength="60"
          />
          <InputError :errors="errorList.surname" />
        </div>
      </div>
      <div class="col-12">
        <div class="flex flex-column gap-1">
          <label for="person_code">{{ $t(`fields.user.person_code`) }}</label>
          <InputMask
            id="person_code"
            v-model="instance.person_code"
            :disabled="fetchDataStore.isFetching || fetchDataStore.isProcessing"
            :invalid="errorList.person_code"
            mask="999999-99999"
            unmask
          />
          <InputError :errors="errorList.person_code" />
        </div>
      </div>
      <div class="col-12">
        <div class="flex flex-column gap-1">
          <label for="birthdate">{{ $t(`fields.user.birthdate`) }}</label>
          <Calendar
            id="birthdate"
            v-model="instance.birthdate"
            :disabled="fetchDataStore.isFetching || fetchDataStore.isProcessing"
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
          <label for="email">{{ $t(`fields.user.email`) }}</label>
          <InputText
            id="email"
            v-model="instance.email"
            :disabled="fetchDataStore.isFetching || fetchDataStore.isProcessing"
            :invalid="errorList.email"
          />
          <InputError :errors="errorList.email" />
        </div>
      </div>
      <div class="col-12">
        <div class="flex flex-column gap-1">
          <label for="phone">{{ $t(`fields.user.phone`) }}</label>
          <vue-tel-input
            v-model="instance.phone"
            :auto-format="true"
            autocomplete="off"
            mode="international"
          >
            <template #input="{ props, actions, value, update }">
              <InputText
                ref="phone"
                :disabled="
                  fetchDataStore.isFetching || fetchDataStore.isProcessing
                "
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
            :disabled="fetchDataStore.isFetching || fetchDataStore.isProcessing"
            :invalid="errorList.iban_code?.length > 0"
            mask="aa*************?******************"
          />
          <InputError :errors="errorList.iban_code" />
        </div>
      </div>
      <div class="col-12">
        <div class="flex flex-column gap-1">
          <label for="password">{{ $t(`fields.user.password`) }}</label>
          <Password
            id="password"
            v-model="instance.password"
            :disabled="fetchDataStore.isFetching || fetchDataStore.isProcessing"
            :invalid="errorList.password?.length > 0"
            inputStyle="width: 100%"
            toggleMask
          />
          <InputError :errors="errorList.password" />
        </div>
      </div>
      <div class="col-12">
        <div class="flex flex-column gap-1">
          <label for="password_confirmation">{{
            $t(`fields.user.password_confirmation`)
          }}</label>
          <Password
            id="password_confirmation"
            v-model="instance.password_confirmation"
            :disabled="fetchDataStore.isFetching || fetchDataStore.isProcessing"
            :invalid="errorList.password_confirmation?.length > 0"
            inputStyle="width: 100%"
            toggleMask
          />
          <InputError :errors="errorList.password_confirmation" />
        </div>
      </div>
      <div class="col-12">
        <label for="roles">{{ $t(`fields.user.roles`) }}</label>
        <div class="row">
          <div
            v-for="(role, index) in instance.all_roles"
            :key="index"
            class="col-12 col-md-4 d-flex align-items-center"
          >
            <ToggleButton
              v-model="role.userHas"
              :disabled="
                fetchDataStore.isFetching || fetchDataStore.isProcessing
              "
              :invalid="errorList.role?.name"
              :off-label="role.name"
              :on-label="role.name"
              class="w-full h-full"
              @update:modelValue="console.log(instance.roles)"
            />
          </div>
        </div>
      </div>
    </form>
  </AdminForm>
</template>

<style scoped></style>
