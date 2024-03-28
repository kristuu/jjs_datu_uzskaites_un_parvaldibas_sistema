<script setup>
import axios from "@/services/axios";
import {computed, inject, onMounted, ref} from "vue";
import { useAuthStore } from "@/stores/authStore";
import { useFetchDataStore } from "@/stores/fetchDataStore";
import { useErrorStore } from "@/stores/errorStore";
import { useRoute } from "vue-router";
import { useI18n } from "vue-i18n";
import router from "@/router/router";

const authStore = useAuthStore();
const fetchDataStore = useFetchDataStore();
const errorStore = useErrorStore();
const route = useRoute();
const locale = useI18n();

const path = computed(() => route.path);
const authorized = computed(() => authStore.authorized);
const user = computed(() => authStore.user);

const items = ref([
  {
    label: locale.t("navigation.home"),
    icon: "bi bi-house",
    command: () => {
      router.push({ name: "HomePage" });
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
    await errorStore.displayError(error);
  }
}
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
          <a class="flex align-items-center ml-auto" v-bind="props.action">
            <span :class="item.icon" />
            <span class="ml-2">{{ item.label }}</span>
            <Badge v-if="item.badge" :class="{ 'ml-auto' : !root, 'ml-2' : root }" :value="item.badge" />
            <span v-if="item.shortcut" class="ml-auto border-1 surface-border surface-100 text-xs p-1">{{ item.shortcut }}</span>
            <i v-if="hasSubmenu" :class="['pi pi-angle-down', { 'pi-angle-down ml-2': root, 'pi-angle-right ml-auto': !root }]"></i>
          </a>
        </template>
        <template #end class="ml-5">
          <div class="flex align-items-center gap-2">
<!--            <InputText :placeholder="$t('table.search')" type="text" class="w-8rem sm:w-auto" />-->
            <Avatar shape="circle" color="white">KB</Avatar>
          </div>
        </template>
      </Menubar>
    </div>
  </div>
</template>

<style scoped>
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