<template>
  <Transition name="fade" mode="out-in">
    <div v-if="show">
      <div class="mt-4 container-xl text-black" style="max-width: 480px;">
        <div class="bg-white shadow rounded-2 p-3">
          <div class="row">
            <h1 :class="errorStore.mainLoginError ? `mb-0` : `mb-4`" style="font-weight:900">AUTORIZĀCIJA</h1>
            <div class="col-12 flex flex-column gap-1">
              <Message severity="error" v-if="errorStore.mainLoginError" :closable="false">
                <span>{{ errorStore.mainLoginError }}</span>
              </Message>
              <label>E-pasta adrese</label>
              <InputText
                  v-model="instance.email"
                  class="w-100"
                  :invalid="errorList.email"
                  :disabled="fetchDataStore.isProcessing"
              />
              <InputError :errors="errorList.email" />
            </div>
            <div class="col-12 flex flex-column gap-1">
              <label>Parole</label>
              <Password
                  class="d-flex flex-column"
                  v-model="instance.password"
                  :feedback="false"
                  :invalid="errorList.password"
                  :disabled="fetchDataStore.isProcessing"
              />
              <InputError :errors="errorList.password" />
            </div>
          </div>
          <div class="mt-3 col-12 text-center">
            <router-link :to="{ name: `RegisterPage` }" class="text-decoration-none text-black">
              <Button text>Nepieciešams reģistrēties?</Button>
            </router-link>
          </div>
        </div>
        <div class="mt-2">
          <Button severity="primary" label="IENĀKT" raised @click="useAuthStore().login"/>
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

</style>