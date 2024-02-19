<script setup>
import { provide, ref, nextTick, computed } from 'vue';
import { useRoute, useRouter } from "vue-router";
import { Toast } from 'bootstrap';
import { useStore } from 'vuex';

import NavigationBar from './components/NavigationBar.vue';
import { lv } from '@/assets/translations/routes/translations';
import Loading from "vue-loading-overlay";
import 'vue-loading-overlay/dist/css/index.css';

const translations = lv;
const router = useRouter();
const route = useRoute();
const store = useStore();
const isLoading = computed(() => store.state.isLoading);

const notifications = ref([]);


const addToastNotification = (notification) => {
  const id = Date.now().toString();

  notification.id = id;
  notification.type = notification.type || 'info';
  notification.autohide = notification.autohide !== undefined ? notification.autohide : true;
  notification.delay = notification.delay || 5000;

  notifications.value.push(notification);

  nextTick(() => {
    const toastEl = document.getElementById(`toast-${id}`);

    new Toast(toastEl, {
      autohide: notification.autohide,
      delay: notification.delay
    }).show();
  });
};

const toastTypeClass = (statusCode) => {
  switch (true) {
    case statusCode >= 200 && statusCode <= 299:
      return 'success';
    case statusCode >= 300 && statusCode <= 399:
      return 'warning';
    case statusCode >= 400 && statusCode <= 599:
      return 'danger';
    default:
      return 'info';
  }
};

const resolveBreadcrumb = (name) => {
  const breadcrumb = translations[name];
  return !(breadcrumb === undefined) ? Array.isArray(breadcrumb) ? breadcrumb : [breadcrumb] : [{ name }];
}

const crumbs = computed(() => {
  const pathArray = route.fullPath.split('/').filter(x => x);
  const crumbs = [];

  pathArray.forEach((path, idx) => {
    let pathToLookup = '/' + pathArray.slice(0, idx + 1).join('/');
    let routeResolved = router.resolve({ path: pathToLookup });

    // Ignore dynamic route segments (e.g., IDs)
    if (routeResolved.name && translations[routeResolved.name]) {
      let breadcrumb = resolveBreadcrumb(routeResolved.name);

      if (breadcrumb) {
        breadcrumb = breadcrumb.map((breadcrumb, i, arr) => ({
          text: breadcrumb.name,
          to: breadcrumb.path || pathToLookup,
          isActive: i === arr.length-1 && idx === pathArray.length-1
        }));

        crumbs.push(...breadcrumb);
      }
    }
  });

  return crumbs;
});

provide('addToastNotification', addToastNotification);
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
