<script>
import { provide, ref, nextTick } from 'vue';
import { Toast } from 'bootstrap';
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
    toastTypeClass(statusCode) {
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
    },
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
  setup() {
    const notifications = ref([]);
    const addToastNotification = (notification) => {
      const id = Date.now().toString();

      // add the id to the notification data
      notification.id = id;

      notification.type = notification.type || 'info';

      // if autohide is not set, default it to true
      notification.autohide = notification.autohide !== undefined ? notification.autohide : true;

      // if delay is not set, default it to 5000 ms
      notification.delay = notification.delay || 5000;

      notifications.value.push(notification);

      nextTick(() => {
        const toastEl = document.getElementById(`toast-${id}`);

        // "destroy" the previous instance if any and create a new toast instance
        new Toast(toastEl, {
          autohide: notification.autohide,
          delay: notification.delay
        }).show();
      });
    };

    provide('addToastNotification', addToastNotification);

    return { notifications };
  },
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
