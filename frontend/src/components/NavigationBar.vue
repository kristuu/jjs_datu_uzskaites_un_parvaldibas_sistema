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
// const logout = () => store.dispatch('logout');

const items = ref([
  {
    label: locale.t("navigation.home"),
    icon: "bi bi-house",
    command: () => {
      router.push({ name: 'HomePage' });
    }
  }
]);

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
  <div class="navbar navbar-overlap">
    <div class="container-xl">
      <Menubar :model="items" class="w-100 border-none align-items-center">
        <template #start>
          <img src="@/assets/logo-white.svg" style="width: 50px; height: auto;"
               class="h-2rem" @click="changeLocale"/>
        </template>
        <template #item="{ item, props, hasSubmenu, root }">
          <a v-ripple class="flex align-items-center ml-auto" v-bind="props.action">
            <span :class="item.icon" />
            <span class="ml-2">{{ item.label }}</span>
            <Badge v-if="item.badge" :class="{ 'ml-auto' : !root, 'ml-2' : root }" :value="item.badge" />
            <span v-if="item.shortcut" class="ml-auto border-1 surface-border surface-100 text-xs p-1">{{ item.shortcut }}</span>
            <i v-if="hasSubmenu" :class="['pi pi-angle-down', { 'pi-angle-down ml-2': root, 'pi-angle-right ml-auto': !root }]"></i>
          </a>
        </template>
        <template #end class="ml-5">
          <div class="flex align-items-center gap-2">
            <InputText :placeholder="$t('table.search')" type="text" class="w-8rem sm:w-auto" />
            <Avatar shape="circle" color="white">KB</Avatar>
          </div>
        </template>
      </Menubar>
    </div>
  </div>

  <Button class="btn btn-primary" />

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