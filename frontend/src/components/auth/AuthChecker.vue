<template>
<component :is="Component">
  <router-view v-if="route.meta.public || store.authorized" />
</component>
</template>

<script setup>
import { computed } from "vue";
import { useAuthStore } from "@/stores/authStore";
import { useRoute, useRouter } from "vue-router";
import { onMounted } from "vue";

const route = useRoute();
const router = useRouter();
const store = useAuthStore();
const Component = computed(() => route.meta.component);

onMounted(async() => {
  await store.checkAuth();
  if (route.meta.public || store.authorized) {

  } else {
    await router.push({name: "LoginPage"});
  }
})
</script>

<style scoped>

</style>