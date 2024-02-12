<script>
import NavigationBar from './components/NavigationBar.vue';
import { lv } from '@/assets/translations/routes/translations';

export default {
  name: 'App',
  data() {
    return {
      user: null,
      translations: lv,
    };
  },
  components: {
    NavigationBar,
  },
  methods: {
    resolveBreadcrumb(name) {
      let breadcrumb = this.translations[name];
      if (breadcrumb === undefined) {
        return [{ name: name }];
      } else if (!Array.isArray(breadcrumb)) {
        breadcrumb = [breadcrumb];
      }
      return breadcrumb;
    }
  },
  computed: {
    crumbs() {
      const pathArray = this.$route.fullPath.split('/').filter(el => el);
      let crumbs = [];

      pathArray.forEach((path, index) => {
        let route = this.$router.resolve({ path: '/' + pathArray.slice(0, index + 1).join('/') });
        let breadcrumbs = route ? this.resolveBreadcrumb(route.name) : [{name: path}];
        crumbs = crumbs.concat(breadcrumbs.map((breadcrumb, i, arr) => ({
          text: breadcrumb.name,
          to: breadcrumb.path || '/' + pathArray.slice(0, index + 1).join('/'),
          isActive: i === arr.length-1 && index === pathArray.length-1 // set as active if it's last crumb
        })));
      });

      return crumbs;
    },
  },
  mounted() {

  },
  beforeUnmount() {
    window.removeEventListener('resize', this.adjustContentPadding);
  }
}
</script>

<template>
  <NavigationBar />
  <div id="app">
    <main class="container-xxl">
      <nav style="--bs-breadcrumb-divider: '>'" aria-label="breadcrumb"
           class="d-flex justify-content-end">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><router-link to="/" class="text-capitalize">Sākums</router-link></li>
          <li v-for="(crumb, i) in crumbs" :key="i"
              class="breadcrumb-item" :class='i + 1 === crumbs.length ? "active" : ""'
              :aria-current='i + 1 === crumbs.length ? "page" : ""'>
              <router-link :to="crumb.to"
                           class="text-capitalize"
                           v-if="i + 1 !== crumbs.length">{{ crumb.text }}</router-link>
              {{ i + 1 === crumbs.length ? crumb.text : "" }}
          </li>
        </ol>
      </nav>
      <router-view />
    </main>
  </div>
</template>

<style>
#app {
  font-family: "Open Sans", sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  color: #2c3e50;
}

h1, h2, h3, h4, h5, h6, label, .nav-link {
  font-family: Ubuntu, sans-serif;
}
</style>
