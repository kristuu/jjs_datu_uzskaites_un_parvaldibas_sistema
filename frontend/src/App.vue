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
            text: breadcrumb.name,
            to: breadcrumb.path || path,
            isActive: i === arr.length - 1 && matchedRoute === matchedRoutes[matchedRoutes.length - 1]
          };
        });

        crumbs.push(...breadcrumb);
      }
    }
  });

  return crumbs;
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
      <nav style="--bs-breadcrumb-divider: '//'" aria-label="breadcrumb"
           class="d-flex justify-content-end">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><router-link to="/" class="text-capitalize">{{ $t("breadcrumbs.home") }}</router-link></li>
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
  <div aria-live="polite" aria-atomic="true" class="position-relative">
    <div class="toast-container top-0 end-0 p-3 position-fixed">
      <div v-for="notification in notifications"
           :key="notification.id"
           :id="`toast-${ notification.id }`"
           class="toast" role="alert" aria-live="assertive"
           :class="'bg-' + toastTypeClass(notification.status) + '-subtle'"
           aria-atomic="true">
        <div class="toast-header"
             :class="'text-' + toastTypeClass(notification.status)">
          <strong class="me-auto">{{ notification.title }}</strong>
          <button class="bi bi-x-lg fs-5"
                  :class="'text-' + toastTypeClass(notification.status)"
                  style="border: none; background: none;"
                  data-bs-dismiss="toast"
                  aria-label="Close"></button>
        </div>
        <div class="toast-body"
             :class="'text-' + toastTypeClass(notification.status)">
          {{ notification.message }}
        </div>
      </div>
    </div>
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

h1, h2, h3, h4, h5, h6, label, .nav-link {
  font-family: Ubuntu, sans-serif;
}
</style>
