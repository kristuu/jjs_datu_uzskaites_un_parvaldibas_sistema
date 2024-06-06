<template>
  <form id="editUserForm" class="row py-3 text-start needs-validation">
    <div class="col-12 sm:col-4">
      <div class="flex flex-column gap-1 align-items-center">
        <label for="profile_picture">{{
          $t("fields.user.profile_picture")
        }}</label>
        <div v-if="showPicture">
          <Image
            v-if="
              !cropperImage && (previewImage || authStore.user.profile_picture)
            "
            :src="previewImage ?? authStore.user.profile_picture"
            alt="Profile Picture"
            class="mt-2"
            preview
            width="150px"
          />
        </div>
        <div v-if="showCropper">
          <vue-cropper
            v-if="cropperImage"
            ref="cropperRef"
            :aspect-ratio="2 / 3"
            :src="cropperImage"
            :view-mode="1"
          ></vue-cropper>
        </div>
        <FileUpload
          :chooseLabel="$t(`fields.user.choose_photo`)"
          accept="image/*"
          custom-upload
          mode="basic"
          name="profile_picture"
          style="font-size: 1rem; font-family: Ubuntu, sans-serif"
          @remove="onFileRemove"
          @select="onFileSelect"
        />
        <Button
          v-if="showCropper"
          :label="$t(`actions.cancel_photo_change`)"
          class="p-button-danger mt-2"
          @click="onFileRemove"
        />
        <InputError :errors="errorList.profile_picture" />
      </div>
    </div>
    <div class="col-12 sm:col-8 row">
      <div class="col-12 md:col-6">
        <div class="flex flex-column gap-1">
          <label for="name">{{ $t("fields.user.name") }}</label>
          <InputText
            id="name"
            v-model="instance.name"
            :disabled="fetchDataStore.isFetching || fetchDataStore.isProcessing"
            :invalid="errorList.name"
            maxlength="60"
          />
          <InputError :errors="errorList.name" />
        </div>
      </div>
      <div class="col-12 md:col-6">
        <div class="flex flex-column gap-1">
          <label for="surname">{{ $t("fields.user.surname") }}</label>
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
      <div class="col-12 md:col-6">
        <div class="flex flex-column gap-1">
          <label for="email">{{ $t("fields.user.email") }}</label>
          <InputText
            id="email"
            v-model="instance.email"
            :disabled="fetchDataStore.isFetching || fetchDataStore.isProcessing"
            :invalid="errorList.email"
          />
          <InputError :errors="errorList.email" />
        </div>
      </div>
      <div class="col-12 md:col-6">
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
    </div>
    <div class="col-12 text-end">
      <Button
        :label="$t(`actions.save`).toLocaleUpperCase()"
        :loading="fetchDataStore.isProcessing"
        @click="saveProfile"
      />
    </div>
  </form>
</template>

<script setup>
import { computed, ref } from "vue";
import { useFetchDataStore } from "@/stores/fetchDataStore";
import { useErrorStore } from "@/stores/errorStore";
import { useAuthStore } from "@/stores/authStore";
import axios from "@/services/axios";
import FileUpload from "primevue/fileupload";
import InputError from "@/components/error/inputError.vue";
import VueCropper from "vue-cropperjs";
import { useToast } from "primevue/usetoast";

const fetchDataStore = useFetchDataStore();
const errorStore = useErrorStore();
const toast = useToast();
const authStore = useAuthStore();

const instance = computed(() => fetchDataStore.instance);
const errorList = computed(() => errorStore.errorList);

let previewImage = ref(null);
let cropperImage = ref(null);

let showPicture = ref(true);
let showCropper = ref(false);

let baseImage = ref(null);

const cropperRef = ref(null);

const onFileSelect = (event) => {
  const file = event.files[0];
  const reader = new FileReader();
  reader.onload = (e) => {
    showCropper.value = true;
    showPicture.value = false;
    cropperImage.value = e.target.result;
    previewImage.value = null;
  };
  reader.readAsDataURL(file);
};

const onFileRemove = () => {
  cropperImage.value = null;
  showCropper.value = false;
  showPicture.value = true;
  previewImage.value = authStore.user.profile_picture;
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
      cropperImage.value = null;
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
      summary: "Profila atjaunināšana",
      detail: "Profila informācija tika veiksmīgi atjaunināta",
      life: 3000,
    });
  } catch (error) {
    console.error(error);
    if (error.response && error.response.status === 422) {
      errorStore.setErrorList(error.response.data);
    }
  } finally {
    fetchDataStore.setIsProcessing(false);
    showCropper.value = false;
    showPicture.value = true;
    previewImage.value = instance.value.profile_picture;
  }
};
</script>
