<template>
  <Transition mode="out-in" name="fade">
    <div v-if="fetchDataStore.show" class="container-xl mt-3 lg:mt-0">
      <div class="grid">
        <!-- Left Sidebar -->
        <div class="col-12 lg:col-3">
          <div class="grid">
            <div class="col-12 h-full">
              <div class="grid">
                <div class="col-12">
                  <div
                    class="p-2 bg-primary lg:bg-white rounded shadow text-center h-full"
                  >
                    <h1 class="m-0 text-white lg:text-primary">
                      {{ $t("user_profile").toLocaleUpperCase() }}
                    </h1>
                  </div>
                </div>
                <div class="col-12">
                  <div class="p-2 bg-primary lg:bg-white rounded shadow h-full">
                    <div>
                      <p class="m-0 fw-bold text-white lg:text-primary">
                        {{ $t("authorized_as").toLocaleUpperCase() }}
                      </p>
                      <p class="m-0 text-white lg:text-primary">
                        {{ `${authStore.user.name} ${authStore.user.surname}` }}
                      </p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Main Content -->
        <div class="col-12 lg:col-9">
          <div class="p-3 bg-white rounded shadow h-full">
            <TabView>
              <!-- User Information Tab -->
              <TabPanel :header="$t(`tabpanel.user.info`)">
                <form
                  id="editUserForm"
                  class="row gap-3 py-3 text-start needs-validation"
                >
                  <div class="col-12">
                    <div class="flex flex-column gap-1">
                      <label for="name">{{ $t("fields.user.name") }}</label>
                      <InputText
                        id="name"
                        v-model="instance.name"
                        :disabled="
                          fetchDataStore.isFetching ||
                          fetchDataStore.isProcessing
                        "
                        :invalid="errorList.name"
                        maxlength="60"
                      />
                      <InputError :errors="errorList.name" />
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="flex flex-column gap-1">
                      <label for="surname">{{
                        $t("fields.user.surname")
                      }}</label>
                      <InputText
                        id="surname"
                        v-model="instance.surname"
                        :disabled="
                          fetchDataStore.isFetching ||
                          fetchDataStore.isProcessing
                        "
                        :invalid="errorList.surname"
                        maxlength="60"
                      />
                      <InputError :errors="errorList.surname" />
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="flex flex-column gap-1">
                      <label for="email">{{ $t("fields.user.email") }}</label>
                      <InputText
                        id="email"
                        v-model="instance.email"
                        :disabled="
                          fetchDataStore.isFetching ||
                          fetchDataStore.isProcessing
                        "
                        :invalid="errorList.email"
                      />
                      <InputError :errors="errorList.email" />
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="flex flex-column gap-1">
                      <label for="phone">{{ $t("fields.user.phone") }}</label>
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
                              fetchDataStore.isFetching ||
                              fetchDataStore.isProcessing
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
                      <label for="iban_code">{{
                        $t(`fields.user.iban_code`)
                      }}</label>
                      <InputMask
                        id="iban_code"
                        v-model="instance.iban_code"
                        :disabled="
                          fetchDataStore.isFetching ||
                          fetchDataStore.isProcessing
                        "
                        :invalid="errorList.iban_code?.length > 0"
                        mask="aa*************?******************"
                      />
                      <InputError :errors="errorList.iban_code" />
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="flex flex-column gap-1">
                      <label for="profile_picture">{{
                        $t("fields.user.profile_picture")
                      }}</label>
                      <img
                        v-if="previewImage || authStore.user.profile_picture"
                        :src="previewImage ?? authStore.user.profile_picture"
                        alt="Profile Picture"
                        class="mt-2"
                        style="max-width: 150px"
                      />
                      <vue-cropper
                        v-if="cropperImage"
                        ref="cropperRef"
                        :aspect-ratio="2 / 3"
                        :src="cropperImage"
                        :view-mode="1"
                        class="w-10rem"
                      ></vue-cropper>
                      <FileUpload
                        :chooseLabel="$t(`fields.user.choose_photo`)"
                        accept="image/*"
                        mode="basic"
                        name="profile_picture"
                        style="font-size: 1rem; font-family: Ubuntu, sans-serif"
                        @select="onFileSelect"
                      />
                      <InputError :errors="errorList.profile_picture" />
                    </div>
                  </div>
                  <div class="col-12">
                    <Button
                      :loading="fetchDataStore.isProcessing"
                      label="Save"
                      @click="saveProfile"
                    />
                  </div>
                </form>
              </TabPanel>

              <!-- Change Password Tab -->
              <TabPanel :header="$t(`tabpanel.user.change_password`)">
                <form
                  id="changePasswordForm"
                  class="row gap-3 py-3 text-start needs-validation"
                >
                  <div class="col-12">
                    <div class="flex flex-column gap-1">
                      <label for="old_password">{{
                        $t("fields.user.old_password")
                      }}</label>
                      <Password
                        id="old_password"
                        v-model="password.old_password"
                        :disabled="
                          fetchDataStore.isFetching ||
                          fetchDataStore.isProcessing
                        "
                        :invalid="errorList.old_password?.length > 0"
                        inputStyle="width: 100%"
                        toggleMask
                      />
                      <InputError :errors="errorList.old_password" />
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="flex flex-column gap-1">
                      <label for="new_password">{{
                        $t("fields.user.new_password")
                      }}</label>
                      <Password
                        id="new_password"
                        v-model="password.new_password"
                        :disabled="
                          fetchDataStore.isFetching ||
                          fetchDataStore.isProcessing
                        "
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
                        :disabled="
                          fetchDataStore.isFetching ||
                          fetchDataStore.isProcessing
                        "
                        :invalid="
                          errorList.new_password_confirmation?.length > 0
                        "
                        inputStyle="width: 100%"
                        toggleMask
                      />
                      <InputError
                        :errors="errorList.new_password_confirmation"
                      />
                    </div>
                  </div>
                  <div class="col-12">
                    <Button
                      :loading="fetchDataStore.isProcessing"
                      label="Change Password"
                      @click="changePassword"
                    />
                  </div>
                </form>
              </TabPanel>
            </TabView>
          </div>
        </div>
      </div>
    </div>
  </Transition>
</template>

<script setup>
import { computed, onBeforeMount, onMounted, ref } from "vue";
import { useFetchDataStore } from "@/stores/fetchDataStore";
import { useErrorStore } from "@/stores/errorStore";
import { useAuthStore } from "@/stores/authStore";
import { useToast } from "primevue/usetoast";
import axios from "@/services/axios";
import FileUpload from "primevue/fileupload";
import InputError from "@/components/error/inputError.vue";
import VueCropper from "vue-cropperjs";
import "cropperjs/dist/cropper.css";

const fetchDataStore = useFetchDataStore();
const errorStore = useErrorStore();
const authStore = useAuthStore();
const toast = useToast();

const instance = computed(() => fetchDataStore.instance);
const errorList = computed(() => errorStore.errorList);

const password = ref({
  old_password: "",
  new_password: "",
  new_password_confirmation: "",
});

let previewImage = ref(null);
let cropperImage = ref(null);
let baseImage = ref(null);

const cropperRef = ref(null);

const onFileSelect = (event) => {
  const file = event.files[0];
  const reader = new FileReader();
  reader.onload = (e) => {
    cropperImage.value = e.target.result;
  };
  reader.readAsDataURL(file);
};

const getCroppedImage = () => {
  return new Promise((resolve) => {
    const cropper = cropperRef.value.cropper;
    cropper.getCroppedCanvas().toBlob((blob) => {
      const reader = new FileReader();
      reader.onload = (e) => {
        resolve(e.target.result);
      };
      reader.readAsDataURL(blob);
    });
  });
};

const uploadImage = async (base64Image) => {
  try {
    const imageResponse = await axios.post("/api/upload-image", {
      image: base64Image,
    });
    instance.value.profile_picture = imageResponse.data.data.link; // Assuming Imgur returns the image link in `data.data.link`
  } catch (error) {
    console.error("Error uploading image:", error);
  }
};

const saveProfile = async () => {
  fetchDataStore.setIsProcessing(true);

  try {
    if (cropperImage.value) {
      baseImage.value = await getCroppedImage();
      await uploadImage(baseImage.value.split(",")[1]);
    }

    // Save user profile
    await axios.put("/api/user/profile", {
      name: instance.value.name,
      surname: instance.value.surname,
      email: instance.value.email,
      phone: instance.value.phone,
      profile_picture: instance.value.profile_picture,
      iban_code: instance.value.iban_code,
    });

    toast.add({
      severity: "success",
      summary: "Profile Updated",
      detail: "Your profile has been updated successfully",
      life: 3000,
    });
  } catch (error) {
    console.error(error);
    if (error.response && error.response.status === 422) {
      errorStore.setErrorList(error.response.data);
    }
  } finally {
    fetchDataStore.setIsProcessing(false);
  }
};

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
      summary: "Password Changed",
      detail: "Your password has been changed successfully",
      life: 3000,
    });
  } catch (error) {
    console.error(error);
  } finally {
    fetchDataStore.setIsProcessing(false);
  }
};

onBeforeMount(() => {
  fetchDataStore.resetInstance();
  fetchDataStore.instance = authStore.user;
});

onMounted(() => {
  fetchDataStore.showComponents();
});
</script>

<style scoped>
.p-timeline-event-opposite {
  display: none;
}
</style>
