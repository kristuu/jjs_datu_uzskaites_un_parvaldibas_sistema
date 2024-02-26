<script setup>
defineProps({
  errors: {
    type: Array,
    required: true,
  }
});
</script>

<template>
  <div v-for="(error, index) in errors" :key="index">
    <InlineMessage>{{ error }}</InlineMessage>
  </div>
</template>

<style scoped>

</style>