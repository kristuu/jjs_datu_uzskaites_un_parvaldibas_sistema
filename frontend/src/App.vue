<script>
import Navbar from './components/NavigationBar.vue';

export default {
  components: {
    Navbar
  }
}
</script>

<template>
  <Navbar />
  <div id="app"></div>
</template>

<style>
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 60px;
}
</style>
