<script>
import NavigationBar from './components/NavigationBar.vue';

export default {
  name: 'App',
  components: {
    NavigationBar,
  },
  methods: {
    // method to adjust content padding on navbar height change.
    adjustContentPadding() {
      const navbar = document.querySelector('.navbar');
      const content = document.querySelector('.content');  // Replace with actual selector of your content.
      content.style.paddingTop = `${navbar.offsetHeight / 2}px`;
    },
  },
  mounted() {
    // handle changes on initial load and when the window is resized
    this.adjustContentPadding();
    window.addEventListener('resize', this.adjustContentPadding);
  },
  beforeUnmount() {
    window.removeEventListener('resize', this.adjustContentPadding);
  }
}
</script>

<template>
  <NavigationBar />
  <div id="app" class="content">
    <router-view />
  </div>
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
