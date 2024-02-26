<script setup>
import { ref, computed } from 'vue';
import { useRoute, useRouter } from "vue-router";
import { useStore } from 'vuex';
import { useI18n } from 'vue-i18n';

import NavigationBar from './components/NavigationBar.vue';
import routeTranslationsLV from '@/locales/lv/routes.json';
import routeTranslationsEN from '@/locales/en/routes.json';
import Loading from "vue-loading-overlay";
import 'vue-loading-overlay/dist/css/index.css';

const router = useRouter();
const route = useRoute();
const store = useStore();
const locale = useI18n();
const isLoading = computed(() => store.state.isLoading);

let routeTranslations = ref(routeTranslationsLV);

/**
 * Resolves the breadcrumb based on the given name.
 *
 * @param {string} name - The name to lookup the breadcrumb.
 * @return {Array<Object>} - The resolved breadcrumb.
 */
const resolveBreadcrumb = (name) => {
  routeTranslations.value = locale.locale.value === 'lv' ? routeTranslationsLV : routeTranslationsEN;
  const breadcrumb = routeTranslations.value[name];
  return !(breadcrumb === undefined) ? Array.isArray(breadcrumb) ? breadcrumb : [breadcrumb] : [{ name }];
}

/**
 * Returns an array of breadcrumb objects based on the current route.
 *
 * @returns {Array} An array of breadcrumb objects.
 */
const crumbs = computed(() => {
  const matchedRoutes = router.resolve({ path: route.fullPath }).matched;
  const crumbs = [];

  matchedRoutes.forEach((matchedRoute) => {
    console.log(`lalalla`);
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

<template>
  <transition name="fade" mode="out-in">
    <div class="vl-parent" v-if="isLoading">
      <Loading v-model:active="isLoading"
               :loader="`bars`"
               :can-cancel="false"
               :is-full-page="true"
               :color="`#9A2E26FF`"
               :opacity="1"
               :width="`6rem`"
               :height="`6rem`"/>
    </div>
  </transition>
  <NavigationBar />
  <div id="app">
    <main class="container-xl">
      <div class="flex justify-content-end mt-2 mb-4">
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

<style>
.fade-enter,
.fade-leave-to {
  opacity: 0;
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.75s;
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
