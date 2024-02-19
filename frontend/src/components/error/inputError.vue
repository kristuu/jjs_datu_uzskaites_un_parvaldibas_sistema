<script setup>
defineProps({
  errors: {
    type: Array,
    required: true,
  }
});
</script>

<template>
  <div v-for="(error, index) in errors" :key="index" class="invalid-feedback">
    {{ error }}
  </div>
</template>

<style scoped>

</style>