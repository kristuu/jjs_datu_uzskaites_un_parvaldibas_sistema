<template>
  <Transition name="fade" mode="out-in">
    <div v-if="show">
      <div class="mt-4 container-xl text-black" style="max-width: 480px;">
        <div class="bg-white shadow rounded-2 p-3">
          <div class="row">
            <h1 class="mb-4" style="font-weight:900">{{ $t(`register`).toLocaleUpperCase() }}</h1>
            <div class="col-12">
              <div class="flex flex-column gap-1">
                <label for="name">{{ $t(`field.name`) }}</label>
                <InputText v-model="instance.name"
                           maxlength="60"
                           pattern="[A-ž\s\-]*"
                           :invalid="errorList.name"
                           :disabled="fetchDataStore.isProcessing"
                           id="name" class="required"/>
                <InputError :errors="errorList.name" />
              </div>
            </div>
            <div class="col-12">
              <div class="flex flex-column gap-1">
                <label for="surname">{{ $t(`field.surname`) }}</label>
                <InputText v-model="instance.surname"
                           maxlength="60"
                           :invalid="errorList.surname"
                           :disabled="fetchDataStore.isProcessing"
                           id="surname"/>
                <InputError :errors="errorList.surname" />
              </div>
            </div>
            <div class="col-12">
              <div class="flex flex-column gap-1">
                <label for="birthdate">{{ $t(`field.birthdate`) }}</label>
                <Calendar v-model="instance.birthdate"
                          :invalid="errorList.birthdate"
                          :disabled="fetchDataStore.isProcessing"
                          id="birthdate"
                          dateFormat="dd.mm.yy"
                          :maxDate="new Date()"
                          panelClass="min-w-min"/>
                <InputError :errors="errorList.birthdate" />
              </div>
            </div>
            <div class="col-12">
              <div class="flex flex-column gap-1">
                <label for="person_code">{{ $t(`field.person_code`) }}</label>
                <InputMask v-model="instance.person_code"
                           mask="999999-99999"
                           :invalid="errorList.person_code"
                           :disabled="fetchDataStore.isProcessing"
                           id="person_code"
                           unmask />
                <InputError :errors="errorList.person_code" />
              </div>
            </div>
            <div class="col-12">
              <div class="flex flex-column gap-1">
                <label for="email">{{ $t(`field.email`) }}</label>
                <InputText v-model="instance.email"
                           :invalid="errorList.email"
                           :disabled="fetchDataStore.isProcessing"
                           id="email" aria-describedby="email-advice"/>
                <small id="email-advice"><i class="bi bi-info-circle mr-1" />{{ $t(`check_email`) }}</small>
                <InputError :errors="errorList.email" />
              </div>
            </div>
            <div class="col-12">
              <div class="flex flex-column gap-1">
                <label for="phone">{{ $t(`field.phone`) }}</label>
                <vue-tel-input v-model="instance.phone"
                               :auto-format="true"
                               :disabled="fetchDataStore.isProcessing"
                               autocomplete="off"
                               mode="international">
                  <template #input="{ props, actions, value, update }">
                    <InputText ref="phone" v-on="{ ...actions }" v-bind="props"
                               :value="value" @input="update($event.target.value)"
                               :invalid="errorList.phone?.length > 0"
                               :disabled="fetchDataStore.isProcessing"
                               aria-describedby="phone-advice" />
                  </template>
                </vue-tel-input>
                <small id="phone-advice"><i class="bi bi-info-circle mr-1" />{{ $t(`check_phone`) }}</small>
                <InputError :errors="errorList.phone" />
              </div>
            </div>
            <div class="col-12 flex flex-column gap-1">
              <label for="password">{{ $t(`field.password`) }}</label>
              <Password
                  class="d-flex flex-column"
                  v-model="instance.password"
                  id="password"
                  :feedback="false"
                  :invalid="errorList.password"
                  :disabled="fetchDataStore.isProcessing"
              />
              <InputError :errors="errorList.password" />
            </div>
            <div class="col-12 flex flex-column gap-1">
              <label for="password_confirmation">{{ $t(`field.password_confirmation`) }}</label>
              <Password
                  class="d-flex flex-column"
                  v-model="instance.password_confirmation"
                  id="password_confirmation"
                  :feedback="false"
                  :invalid="errorList.password_confirmation"
                  :disabled="fetchDataStore.isProcessing"
              />
              <InputError :errors="errorList.password_confirmation" />
            </div>
          </div>
          <div class="mt-3 col-12 text-center">
            <router-link :to="{ name: `LoginPage` }" class="text-decoration-none text-black">
              <Button text>{{ $t(`have_account_already`) }}</Button>
            </router-link>
          </div>
        </div>
        <div class="mt-2">
          <Button severity="primary" :label="$t(`actions.register`).toLocaleUpperCase()" raised @click="useAuthStore().register"/>
        </div>
      </div>
    </div>
  </Transition>
</template>

<script setup>
import { useFetchDataStore } from "@/stores/fetchDataStore";
import {computed, onMounted} from "vue";

import FloatLabel from "primevue/floatlabel";

import {useErrorStore} from "@/stores/errorStore";
import {useAuthStore} from "@/stores/authStore";
import InputError from "@/components/error/inputError.vue";

const fetchDataStore = useFetchDataStore();
const errorStore = useErrorStore();

let show = computed(() => fetchDataStore.show);

let instance = computed(() => fetchDataStore.instance);
let errorList = computed(() => errorStore.errorList);

onMounted(() => {
  fetchDataStore.showComponents();
});
</script>

<style scoped>
  .main-frame {
    max-width: 330px;
    padding: 1rem;
  }
</style>