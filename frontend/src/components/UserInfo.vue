<template>
  <div class="col-12">
    <div class="p-2 bg-primary lg:bg-white rounded shadow w-full">
      <div>
        <p class="m-0 fw-bold text-white lg:text-primary">
          {{ $t(`authorized_as`).toLocaleUpperCase() }}
        </p>
        <p class="m-0 text-white lg:text-primary">
          {{ `${user.name} ${user.surname}` }}
        </p>
      </div>
    </div>
  </div>
  <div class="col-12">
    <div class="p-2 bg-white rounded shadow w-100">
      <div>
        <h3 class="m-0 text-primary text-center">
          {{ $t(`notifications`).toLocaleUpperCase() }}
        </h3>
        <Divider class="my-2" />
        <p class="m-0 text-white lg:text-primary">
          {{ `${user.name} ${user.surname}` }}
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useAuthStore } from "@/stores/authStore";
import { onBeforeMount, onMounted } from "vue";
import { useFetchDataStore } from "@/stores/fetchDataStore";
import axios from "@/services/axios";

const fetchDataStore = useFetchDataStore();

const authStore = useAuthStore();
const user = authStore.user;

onBeforeMount(async () => {
  await axios
    .get(`/api/notifications`)
    .then((response) => {
      console.log(response);
    })
    .catch((error) => {
      console.error(error);
    });
});

onMounted(() => {
  fetchDataStore.showComponents();
});
</script>
