<template>
  <form
    id="changePasswordForm"
    class="row gap-3 mt-3 text-start needs-validation"
  >
    <div class="col-12">
      <div class="flex flex-column gap-1">
        <label for="old_password">{{ $t("fields.user.old_password") }}</label>
        <Password
          id="old_password"
          v-model="password.old_password"
          :disabled="fetchDataStore.isFetching || fetchDataStore.isProcessing"
          :invalid="errorList.old_password?.length > 0"
          inputStyle="width: 100%"
          toggleMask
        />
        <InputError :errors="errorList.old_password" />
      </div>
    </div>
    <div class="col-12">
      <div class="flex flex-column gap-1">
        <label for="new_password">{{ $t("fields.user.new_password") }}</label>
        <Password
          id="new_password"
          v-model="password.new_password"
          :disabled="fetchDataStore.isFetching || fetchDataStore.isProcessing"
          :invalid="errorList.new_password?.length > 0"
          inputStyle="width: 100%"
          toggleMask
        />
        <InputError :errors="errorList.new_password" />
      </div>
    </div>
    <div class="col-12">
      <div class="flex flex-column gap-1">
        <label for="new_password_confirmation">{{
          $t("fields.user.new_password_confirmation")
        }}</label>
        <Password
          id="new_password_confirmation"
          v-model="password.new_password_confirmation"
          :disabled="fetchDataStore.isFetching || fetchDataStore.isProcessing"
          :invalid="errorList.new_password_confirmation?.length > 0"
          inputStyle="width: 100%"
          toggleMask
        />
        <InputError :errors="errorList.new_password_confirmation" />
      </div>
    </div>
    <div class="col-12 text-end">
      <Button
        :label="$t(`actions.save`).toLocaleUpperCase()"
        :loading="fetchDataStore.isProcessing"
        @click="changePassword"
      />
    </div>
  </form>
</template>

<script setup>
import { computed, ref } from "vue";
import { useFetchDataStore } from "@/stores/fetchDataStore";
import { useErrorStore } from "@/stores/errorStore";
import axios from "@/services/axios";
import InputError from "@/components/error/inputError.vue";
import { useToast } from "primevue/usetoast";

const fetchDataStore = useFetchDataStore();
const errorStore = useErrorStore();
const toast = useToast();

const password = ref({
  old_password: "",
  new_password: "",
  new_password_confirmation: "",
});

const errorList = computed(() => errorStore.errorList);

const changePassword = async () => {
  fetchDataStore.setIsProcessing(true);

  try {
    await axios.put("/api/user/change-password", {
      old_password: password.value.old_password,
      new_password: password.value.new_password,
      new_password_confirmation: password.value.new_password_confirmation,
    });

    toast.add({
      severity: "success",
      summary: "Paroles maiņa",
      detail: "Parole tika veiksmīgi nomainīta!",
      life: 3000,
    });
  } catch (error) {
    if (error.response.status === 422) {
      errorStore.setErrorList(error.response.data);
    } else {
      console.error(error);
    }
  } finally {
    fetchDataStore.setIsProcessing(false);
  }
};
</script>
