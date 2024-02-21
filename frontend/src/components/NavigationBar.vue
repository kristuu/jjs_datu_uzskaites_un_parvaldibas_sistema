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