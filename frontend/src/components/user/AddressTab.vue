<template>
  <div>
    <h2>{{ $t("address.edit_address") }}</h2>
    <vue-google-autocomplete
      id="map"
      :debounce="300"
      :placeholder="$t('address.enter_address')"
      class="p-inputtext p-component"
      @placechanged="getAddressData"
    />
    <form @submit.prevent="saveAddress">
      <div class="form-group">
        <label for="address_line1">{{ $t("address.address_line1") }}</label>
        <InputText
          id="address_line1"
          v-model="address.line1"
          :disabled="fetchDataStore.isFetching || fetchDataStore.isProcessing"
          class="p-inputtext p-component"
          required
        />
      </div>
      <div class="form-group">
        <label for="address_line2">{{ $t("address.address_line2") }}</label>
        <InputText
          id="address_line2"
          v-model="address.line2"
          :disabled="fetchDataStore.isFetching || fetchDataStore.isProcessing"
          class="p-inputtext p-component"
        />
      </div>
      <div class="form-group">
        <label for="city">{{ $t("address.city") }}</label>
        <InputText
          id="city"
          v-model="address.city"
          :disabled="fetchDataStore.isFetching || fetchDataStore.isProcessing"
          class="p-inputtext p-component"
          required
        />
      </div>
      <div class="form-group">
        <label for="region">{{ $t("address.region") }}</label>
        <InputText
          id="region"
          v-model="address.region"
          :disabled="fetchDataStore.isFetching || fetchDataStore.isProcessing"
          class="p-inputtext p-component"
          required
        />
      </div>
      <div class="form-group">
        <label for="country">{{ $t("address.country") }}</label>
        <InputText
          id="country"
          v-model="address.country"
          :disabled="fetchDataStore.isFetching || fetchDataStore.isProcessing"
          class="p-inputtext p-component"
          required
        />
      </div>
      <div class="form-group">
        <label for="postal_code">{{ $t("address.postal_code") }}</label>
        <InputText
          id="postal_code"
          v-model="address.postal_code"
          :disabled="fetchDataStore.isFetching || fetchDataStore.isProcessing"
          class="p-inputtext p-component"
          required
        />
      </div>
      <Button
        :label="$t('actions.save')"
        class="p-button p-component p-button-primary"
        type="submit"
      />
    </form>
  </div>
</template>

<script setup>
import { onBeforeMount, ref } from "vue";
import VueGoogleAutocomplete from "vue-google-autocomplete";
import InputText from "primevue/inputtext";
import Button from "primevue/button";
import axios from "@/services/axios";
import { useFetchDataStore } from "@/stores/fetchDataStore";

const fetchDataStore = useFetchDataStore();

const address = ref({
  line1: "",
  line2: "",
  city: "",
  region: "",
  country: "",
  postal_code: "",
});

const getAddressData = (addressData, placeResultData) => {
  address.value = mapAddressComponents(placeResultData.address_components);
};

const mapAddressComponents = (addressComponents) => {
  const componentForm = {
    street_number: "short_name",
    route: "long_name",
    premise: "long_name",
    subpremise: "long_name",
    locality: "long_name",
    administrative_area_level_1: "short_name",
    administrative_area_level_2: "short_name",
    sublocality_level_1: "short_name",
    sublocality: "short_name",
    country: "long_name",
    postal_code: "short_name",
    point_of_interest: "long_name",
    establishment: "long_name",
  };

  const mappedComponents = {};

  addressComponents.forEach((component) => {
    const addressType = component.types[0];
    if (componentForm[addressType]) {
      mappedComponents[addressType] = component[componentForm[addressType]];
    }
  });

  return {
    line1: (
      (mappedComponents.route
        ? `${mappedComponents.route || ""} ${
            mappedComponents.street_number || ""
          }`
        : mappedComponents.premise) ??
      (mappedComponents.point_of_interest || mappedComponents.establishment)
    ).trim(),
    line2: mappedComponents.subpremise || "",
    city:
      (mappedComponents.locality ??
        mappedComponents.administrative_area_level_2) ||
      "",
    region:
      mappedComponents.administrative_area_level_1 ||
      mappedComponents.sublocality_level_1 ||
      mappedComponents.sublocality ||
      "",
    country: mappedComponents.country || "",
    postal_code: mappedComponents.postal_code || "",
  };
};

const saveAddress = async () => {
  fetchDataStore.setIsProcessing(true);
  // await axios.get(`/sanctum/csrf-cookie`);
  await axios
    .post("/api/user/address", {
      address_line1: address.value.line1,
      address_line2: address.value.line2,
      region: address.value.region,
      city: address.value.city,
      country: address.value.country,
      postal_code: address.value.postal_code,
    })
    .then(() => {
      console.log("success");
    })
    .catch((error) => {
      console.error(error);
    })
    .finally(() => {
      fetchDataStore.setIsProcessing(false);
    });
};

onBeforeMount(async () => {
  fetchDataStore.setIsFetching(true);
  const response = await axios.get("/api/user/address");
  address.value.line1 = response.data.address_line1;
  address.value.line2 = response.data.address_line2;
  address.value.region = response.data.region;
  address.value.city = response.data.city.name;
  address.value.country = response.data.city.country.name;
  address.value.postal_code = response.data.postal_code;
  fetchDataStore.setIsFetching(false);
});
</script>

<style scoped></style>
