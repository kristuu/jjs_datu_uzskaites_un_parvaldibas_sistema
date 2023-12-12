<script>
import { mapState, mapActions } from "vuex";

export default {
  computed: {
    // using Vuex helpers to map 'authorized' state to a computed property
    ...mapState(['authorized'])
  },
  methods: {
    // Vuex helper usage to map 'logout' action to a method
    ...mapActions(['logout'])
  }
}
</script>

<template>

</template>

<style scoped>

</style>