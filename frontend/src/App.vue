<template>
  <NavigationBar />
  <div id="app">
    <main class="container-lg">
      <div class="hidden lg:flex justify-content-end mt-2 mb-3">
        <Breadcrumb :home="home" :model="crumbs" class="text-white">
          <template #item="{ item, props }">
            <router-link v-if="item.route" v-slot="{ href, navigate }" :to="item.route" custom>
              <a :href="href" v-bind="props.action" @click="navigate">
                <span :class="item.icon" />
                <span class="font-semibold">{{ item.label }}</span>
              </a>
            </router-link>
            <a v-else :href="item.url" :target="item.target" v-bind="props.action">
              <span>{{ item.label }}</span>
            </a>
          </template>
        </Breadcrumb>
      </div>
      <router-view />
    </main>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useRoute, useRouter } from "vue-router";
import { useFetchDataStore } from "@/stores/fetchDataStore";
import { useI18n } from 'vue-i18n';

import NavigationBar from './components/NavigationBar.vue';
import routeTranslationsLV from '@/locales/lv/routes.json';
import routeTranslationsEN from '@/locales/en/routes.json';
import 'vue-loading-overlay/dist/css/index.css';

const router = useRouter();
const route = useRoute();
const fetchDataStore = useFetchDataStore();
const locale = useI18n();
const isFetching = computed(() => fetchDataStore.isFetching);

let routeTranslations = ref(routeTranslationsLV);

/**
 * Resolve the breadcrumb based on the given name.
 */
const resolveBreadcrumb = (name) => {
  routeTranslations.value = locale.locale.value === 'lv' ? routeTranslationsLV : routeTranslationsEN;
  const breadcrumb = routeTranslations.value[name];
  return !(breadcrumb === undefined) ? Array.isArray(breadcrumb) ? breadcrumb : [breadcrumb] : [{ name }];
}

/**
 * Return an array of breadcrumb objects based on the current route.
 */
const crumbs = computed(() => {
  const matchedRoutes = router.resolve({ path: route.fullPath }).matched;
  const crumbs = [];

  matchedRoutes.forEach((matchedRoute) => {
    if (matchedRoute.name && routeTranslations.value[matchedRoute.name]) {
      let breadcrumb = resolveBreadcrumb(matchedRoute.name);

      if (breadcrumb) {
        breadcrumb = breadcrumb.map((breadcrumb, i, arr) => {
          const path = matchedRoute.redirect
              ? typeof matchedRoute.redirect === "string"
                  ? matchedRoute.redirect
                  : matchedRoute.redirect.path
              : matchedRoute.path;
          return {
            label: breadcrumb.label,
            route: breadcrumb.route || route,
            isActive: i === arr.length - 1 && matchedRoute === matchedRoutes[matchedRoutes.length - 1]
          };
        });

        crumbs.push(...breadcrumb);
      }
    }
  });

  return crumbs;
});

const home = ref({
  icon: 'bi bi-house-door-fill',
  route: '/'
});
</script>

<style>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

.fade-enter-to,
.fade-leave-from {
  opacity: 1;
}

.vl-parent {
  z-index: 9999;
}

#app {
  font-family: "Open Sans", sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  color: #2c3e50;
}

h1, h2, h3, h4, h5, h6, label,
button,
.p-breadcrumb, .p-column-title {
  font-family: Ubuntu, sans-serif;
}
</style>
