<script setup>
import axios from '@/services/axios';
import { computed, onMounted, ref } from 'vue';
import { useStore } from 'vuex';
import { useRoute } from 'vue-router';
import { useI18n } from 'vue-i18n';
import router from "@/router/router";

const store = useStore();
const route = useRoute();
const locale = useI18n();

const path = computed(() => route.path);
const authorized = computed(() => store.state.authorized);
const logout = () => store.dispatch('logout');

const changeLocale = async (newLocale) => {
  locale.value = newLocale;
  console.log(locale.value);
  try {
    await axios.post('/setlocale', { locale: newLocale })
    console.log(`success`)
  } catch (error) {
    store.commit('setErrorStatus', error.status);
    store.commit('setErrorMessage', error.data.message);
    await router.push({ name: 'ErrorView' });
  }
}

let user = ref(null);

onMounted(async () => {
  store.commit('setLoading', true);
  if(authorized.value) {
    try {
      const response = await axios.get('/user')
      user.value = response.data
    } catch (error) {
      if (error.response.status === 401) {
        user.value = null
      } else {
        console.error(error.response.status)
      }
    }
  }
  store.commit('setLoading', false);
})
</script>

<template>
  <header class="navbar-expand-lg top">
    <div class="collapse navbar-collapse" id="navbar-main">
      <div class="d-print-none navbar navbar-expand-lg navbar-dark navbar-overlap yrs-bg-primary">
        <div class="container-xl">
          <ul class="navbar-nav">
            <li class="nav-brand">
              <a class="navbar-brand" href="/"><img src="../assets/logo-white.svg" style="width: 50px; height: auto;"/></a>
            </li>
            <li class="nav-item">
              <router-link class="nav-link" :to="{ name: 'HomePage' }">
                <i class="bi bi-house-fill"></i>
                <span>{{ $t("navigation.home") }}</span>
              </router-link>
            </li>
          </ul>
          <ul class="nav navbar-nav d-md-down-none"></ul>
          <ul class="nav navbar-nav d-flex flex-row flex-shrink-0">
            <li class="nav-item" v-if="can('access admin dashboard')">
              <router-link class="nav-link" :to="{ name: 'AdminDashboard' }">
                <i class="bi bi-speedometer"></i>
                <span>ADMIN</span>
              </router-link>
            </li>
            <li class="nav-item me-2 dropdown">
              <a class="nav-link dropdown-toggle text-decoration-none"
                 data-bs-toggle="dropdown"
                 data-toggle="dropdown"
                 data-bs-auto-close="true"
                 role="button"
                 aria-expanded="false"
                 style="cursor: pointer">
                <i class="bi bi-translate"></i>
              </a>
              <ul class="dropdown-menu dropdown-menu-end">
                <li>
                  <a class="dropdown-item"
                     :class="$i18n.locale === 'lv' ? 'active' : ''"
                     @click="changeLocale('lv')">
                    <span>[LV] Latviešu</span>
                  </a>
                  <a class="dropdown-item"
                     :class="$i18n.locale === 'en' ? 'active' : ''"
                     @click="changeLocale('en')">
                    <span>[EN] English</span>
                  </a>
                </li>
              </ul>
            </li>
            <div class="nav-item dropdown">
              <a href="#" class="nav-link d-flex lh-1 text-end p-0"
                 data-bs-toggle="dropdown" aria-expanded="false">
                <div class="d-none d-xl-block pe-2">
                  <div class="fw-bold">{{ user ? user.name : '' }}</div>
                  <div class="mt-1 small">Izstrādātājs</div>
                </div>
                <span class="bg-white avatar fw-bold text-black-50">
                  {{ user ? user.name[0].toUpperCase() + user.surname[0].toUpperCase() : "" }}
                </span>
              </a>
              <div class="dropdown-menu dropdown-menu-end">
                <a href="#" class="dropdown-item">
                  <i class="bi bi-person-gear"></i>
                  <span>Mans profils</span>
                </a>
                <div class="dropdown-divider"></div>
                <a @click="logout" class="dropdown-item">
                  <i class="bi bi-lock"></i>
                  <span>Beigt sesiju</span>
                </a>
              </div>
            </div>
          </ul>
        </div>
      </div>
    </div>
  </header>
  <aside class="navbar navbar-expand-lg navbar-dark d-block d-lg-none yrs-bg-primary">
    <div class="container-fluid">
      <ul class="nav navbar-nav d-flex flex-row align-items-center justify-content-between w-100">
        <button class="navbar-toggler p-0 collapsed"
                type="button" data-bs-toggle="collapse"
                data-bs-target="#mobileMainNav" aria-controls="mobileMainNav"
                aria-expanded="false" aria-label="Toggle main navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="d-flex flex-row align-items-center">
          <li class="nav-item me-2 dropdown">
            <a class="nav-link dropdown-toggle text-decoration-none"
               data-bs-toggle="dropdown"
               data-toggle="dropdown"
               data-bs-auto-close="outside"
               role="button"
               aria-expanded="false"
               style="cursor: pointer">
              <i class="bi bi-translate"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li>
                <a class="dropdown-item active">
                  <span>Latviešu</span>
                </a>
              </li>
            </ul>
          </li>
          <div class="nav-item dropdown">
            <a href="#" class="nav-link d-flex lh-1 text-reset p-0"
               data-bs-toggle="dropdown" aria-label="Open authorized user menu"
               aria-expanded="false">
              <span class="bg-white avatar fw-bold text-black-50">
                {{ user ? user.name[0].toUpperCase() + user.surname[0].toUpperCase() : "" }}
              </span>
              <div class="d-none d-xl-block ps-2">
                <div>{{ user ? user.name : "" }}</div>
              </div>
            </a>
          </div>
        </div>
      </ul>
    </div>
  </aside>
  <div v-if="can('access admin dashboard') && path.startsWith('/admin')">
    <div class="nav-scroller container-xl mt-2 mb-2">
      <nav class="nav rounded-1" aria-label="Admin navigation">
        <li class="nav-item">
          <router-link class="nav-link" :to="{ name: 'AdminDashboard'}">
            <span>{{ $t("navigation.dashboard") }}</span>
          </router-link>
        </li>
        <li class="nav-item">
          <router-link class="nav-link" :to="{ name: 'UserList'}">
            <span>{{ $t("navigation.userList") }}</span>
          </router-link>
        </li>
      </nav>
    </div>
  </div>
</template>

<style scoped>
.navbar .dropdown-menu {
  z-index: 1050;
  position: absolute;
}

.navbar {
  position: relative;
}

.navbar-overlap:after {
  height: 13rem;
  content: "";
  position: absolute;
  top: 100%;
  left: 0;
  right: 0;
  background: inherit;
  z-index: -1;
  box-shadow: inherit;
}

.nav-scroller {
  position: relative;
  z-index: 2;
  height: 2.75rem;
  overflow-y: hidden;
}

.nav-scroller .nav {
  display: flex;
  flex-wrap: nowrap;
  overflow-x: auto;
  text-align: center;
  white-space: nowrap;
  -webkit-overflow-scrolling: touch;
  background-color: rgba(255, 255, 255, 0.05);
}

.avatar {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  height: 35px;
  width: 35px;
  border-radius: 50%;
  font-size: 0.75em;
}
</style>