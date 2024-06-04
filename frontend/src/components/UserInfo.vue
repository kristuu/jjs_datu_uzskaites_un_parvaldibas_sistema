<template>
  <div class="p-2 bg-primary lg:bg-white rounded shadow h-full">
    <div>
      <p class="m-0 fw-bold text-white lg:text-primary">
        {{ $t(`authorized_as`).toLocaleUpperCase() }}
      </p>
      <p class="m-0 text-white lg:text-primary">
        {{ `${user.name} ${user.surname}` }}
      </p>
    </div>
  </div>
</template>

<script setup>
import { useAuthStore } from "@/stores/authStore";

const authStore = useAuthStore();
const user = authStore.user;
</script>
