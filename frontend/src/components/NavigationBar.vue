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

let visible = ref(false);

const showSidebar = () => {
  visible.value = !visible.value;
};
</script>

<template>
  <div class="navbar navbar-overlap">
    <div class="container-xl">
      <Menubar :model="items" class="w-100 border-none align-items-center gap-2">
        <template #start>
          <img src="@/assets/logo-white.svg" style="width: 50px; height: auto;"
               class="h-2rem" @click="changeLocale"/>
        </template>
        <template #menubutton><span/></template>
        <template #item="{ item, props, hasSubmenu, root }">
          <a class="flex" v-bind="props.action">
            <span v-if="item.icon" :class="item.icon" />
            <span :class="item.icon ? `ml-2` : ``">{{ item.label }}</span>
            <Badge v-if="item.badge" :class="{ 'ml-auto' : !root, 'ml-2' : root }" :value="item.badge" />
            <span v-if="item.shortcut" class="ml-auto border-1 surface-border surface-100 text-xs p-1">{{ item.shortcut }}</span>
            <i v-if="hasSubmenu" :class="['pi pi-angle-down', { 'pi-angle-down ml-2': root, 'pi-angle-right ml-auto': !root }]"></i>
          </a>
        </template>
        <template #end class="ml-5">
          <div class="flex align-items-center gap-2">
            <!--            <InputText :placeholder="$t('table.search')" type="text" class="w-8rem sm:w-auto" />-->
            <Button class="bg-white shadow hover-black text-primary"
                    icon="bi bi-list-ul" rounded text
                    @click="showSidebar"
            />
          </div>
        </template>
      </Menubar>
    </div>
  </div>

  <Sidebar v-model:visible="visible" class="w-100 border-0 text-white" position="right">
    <template #container="{ closeCallback }">
      <div class="flex flex-column h-full yrs-bg-primary">
        <div class="flex align-items-center justify-content-between px-4 pt-3 flex-shrink-0">
                <span class="inline-flex align-items-center gap-2">
                    <a class="logo" href="/">
                      <img src="@/assets/logo-white.svg" style="width: 50px; height: auto;"
                           class="h-2rem" @click="changeLocale"/>
                    </a>
                </span>
          <span>
                    <Button type="button" @click="closeCallback" icon="bi bi-x-lg" rounded outlined class="h-2rem w-2rem text-white"></Button>
                </span>
        </div>
        <div class="overflow-y-auto text-white">
          <ul class="list-none p-3 m-0">
            <li>
              <div
                  v-styleclass="{
                                selector: '@next',
                                enterClass: 'hidden',
                                enterActiveClass: 'slidedown',
                                leaveToClass: 'hidden',
                                leaveActiveClass: 'slideup'
                            }"
                  class="p-3 flex align-items-center justify-content-between cursor-pointer p-ripple"
              >
                <span style="font-weight: 900;" class="submenu">GALVENĀ SADAĻA</span>
                <i class="bi bi-chevron-down"></i>
              </div>
              <ul class="list-none px-2 m-0 overflow-hidden">
                <li>
                  <router-link :to="{ name: `HomePage` }" @click="visible = false" v-ripple class="flex align-items-center cursor-pointer p-3 border-round hover:fw-bold transition-duration-150 transition-colors p-ripple">
                    <span class="font-medium">SĀKUMS</span>
                  </router-link>
                </li>
              </ul>
            </li>
            <Divider type="dashed" />
            <li v-if="can(`access admin dashboard`)">
              <div
                  v-styleclass="{
                                selector: '@next',
                                enterClass: 'hidden',
                                enterActiveClass: 'slidedown',
                                leaveToClass: 'hidden',
                                leaveActiveClass: 'slideup'
                            }"
                  class="p-3 flex align-items-center justify-content-between cursor-pointer p-ripple"
              >
                <span style="font-weight: 900;" class="submenu">ADMINISTRĀCIJA</span>
                <i class="bi bi-chevron-down"></i>
              </div>
              <ul class="list-none px-2 m-0 overflow-hidden">
                <li>
                  <router-link :to="{ name: `HomePage` }" @click="visible = false" class="flex align-items-center cursor-pointer p-3 border-round hover:fw-bold transition-duration-150 transition-colors p-ripple">
                    <span class="font-medium">SĀKUMS</span>
                  </router-link>
                </li>
                <Divider type="dotted" v-if="can(`access admin dashboard`)" />
                <li v-if="can(`manage users`)">
                  <div
                      v-styleclass="{
                                selector: '@next',
                                enterClass: 'hidden',
                                enterActiveClass: 'slidedown',
                                leaveToClass: 'hidden',
                                leaveActiveClass: 'slideup'
                            }"
                      class="p-3 flex align-items-center justify-content-between cursor-pointer p-ripple"
                  >
                    <span class="font-medium">LIETOTĀJI</span>
                    <i class="bi bi-chevron-down"></i>
                  </div>
                  <ul class="list-none py-0 pl-3 pr-0 m-0 hidden overflow-y-hidden transition-all transition-duration-400 transition-ease-in-out">
                    <li v-if="can(`list instances`)">
                      <router-link :to="{ name: `UserList` }" @click="visible = false" class="flex align-items-center cursor-pointer p-3 border-round hover:fw-bold transition-duration-150 transition-colors p-ripple">
                        <i class="bi bi-search mr-2" />
                        <span>Saraksts</span>
                      </router-link>
                    </li>
                    <li v-if="can(`create instances`)">
                      <router-link :to="{ name: `CreateUser` }" @click="visible = false" class="flex align-items-center cursor-pointer p-3 border-round hover:fw-bold transition-duration-150 transition-colors p-ripple">
                        <i class="bi bi-plus-lg mr-2" />
                        <span>Pievienošana</span>
                      </router-link>
                    </li>
                  </ul>
                </li>
                <li v-if="can(`manage permissions`)">
                  <div
                      v-styleclass="{
                                selector: '@next',
                                enterClass: 'hidden',
                                enterActiveClass: 'slidedown',
                                leaveToClass: 'hidden',
                                leaveActiveClass: 'slideup'
                            }"
                      class="p-3 flex align-items-center justify-content-between cursor-pointer p-ripple"
                  >
                    <span class="font-medium">TIESĪBAS</span>
                    <i class="bi bi-chevron-down"></i>
                  </div>
                  <ul class="list-none py-0 pl-3 pr-0 m-0 hidden overflow-y-hidden transition-all transition-duration-400 transition-ease-in-out">
                    <li v-if="can(`list instances`)">
                      <router-link :to="{ name: `PermissionList` }" @click="visible = false" class="flex align-items-center cursor-pointer p-3 border-round hover:fw-bold transition-duration-150 transition-colors p-ripple">
                        <i class="bi bi-search mr-2" />
                        <span>Saraksts</span>
                      </router-link>
                    </li>
                    <li v-if="can(`create instances`)">
                      <router-link :to="{ name: `CreatePermission` }" @click="visible = false" class="flex align-items-center cursor-pointer p-3 border-round hover:fw-bold transition-duration-150 transition-colors p-ripple">
                        <i class="bi bi-plus-lg mr-2" />
                        <span>Pievienošana</span>
                      </router-link>
                    </li>
                  </ul>
                </li>
                <li v-if="can(`manage roles`)">
                  <div
                      v-styleclass="{
                                selector: '@next',
                                enterClass: 'hidden',
                                enterActiveClass: 'slidedown',
                                leaveToClass: 'hidden',
                                leaveActiveClass: 'slideup'
                            }"
                      class="p-3 flex align-items-center justify-content-between cursor-pointer p-ripple"
                  >
                    <span class="font-medium">LOMAS</span>
                    <i class="bi bi-chevron-down"></i>
                  </div>
                  <ul class="list-none py-0 pl-3 pr-0 m-0 hidden overflow-y-hidden transition-all transition-duration-400 transition-ease-in-out">
                    <li v-if="can(`list instances`)">
                      <router-link :to="{ name: `RoleList` }" @click="visible = false" class="flex align-items-center cursor-pointer p-3 border-round hover:fw-bold transition-duration-150 transition-colors p-ripple">
                        <i class="bi bi-search mr-2" />
                        <span>Saraksts</span>
                      </router-link>
                    </li>
                    <li v-if="can(`create instances`)">
                      <router-link :to="{ name: `CreateRole` }" @click="visible = false" class="flex align-items-center cursor-pointer p-3 border-round hover:fw-bold transition-duration-150 transition-colors p-ripple">
                        <i class="bi bi-plus-lg mr-2" />
                        <span>Pievienošana</span>
                      </router-link>
                    </li>
                  </ul>
                </li>
                <Divider type="dotted" v-if="can(`manage users`) || can(`manage permissions`) || can(`manage roles`)"/>
                <li v-if="can(`manage countries`)">
                  <div
                      v-styleclass="{
                                selector: '@next',
                                enterClass: 'hidden',
                                enterActiveClass: 'slidedown',
                                leaveToClass: 'hidden',
                                leaveActiveClass: 'slideup'
                            }"
                      class="p-3 flex align-items-center justify-content-between cursor-pointer p-ripple"
                  >
                    <span class="font-medium">VALSTIS</span>
                    <i class="bi bi-chevron-down"></i>
                  </div>
                  <ul class="list-none py-0 pl-3 pr-0 m-0 hidden overflow-y-hidden transition-all transition-duration-400 transition-ease-in-out">
                    <li v-if="can(`list instances`)">
                      <router-link :to="{ name: `CountryList` }" @click="visible = false" class="flex align-items-center cursor-pointer p-3 border-round hover:fw-bold transition-duration-150 transition-colors p-ripple">
                        <i class="bi bi-search mr-2" />
                        <span>Saraksts</span>
                      </router-link>
                    </li>
                    <li v-if="can(`create instances`)">
                      <router-link :to="{ name: `CreateCountry` }" @click="visible = false" class="flex align-items-center cursor-pointer p-3 border-round hover:fw-bold transition-duration-150 transition-colors p-ripple">
                        <i class="bi bi-plus-lg mr-2" />
                        <span>Pievienošana</span>
                      </router-link>
                    </li>
                  </ul>
                </li>
                <li v-if="can(`manage regions`)">
                  <div
                      v-styleclass="{
                                selector: '@next',
                                enterClass: 'hidden',
                                enterActiveClass: 'slidedown',
                                leaveToClass: 'hidden',
                                leaveActiveClass: 'slideup'
                            }"
                      class="p-3 flex align-items-center justify-content-between cursor-pointer p-ripple"
                  >
                    <span class="font-medium">REĢIONI</span>
                    <i class="bi bi-chevron-down"></i>
                  </div>
                  <ul class="list-none py-0 pl-3 pr-0 m-0 hidden overflow-y-hidden transition-all transition-duration-400 transition-ease-in-out">
                    <li v-if="can(`list instances`)">
                      <router-link :to="{ name: `RegionList` }" @click="visible = false" class="flex align-items-center cursor-pointer p-3 border-round hover:fw-bold transition-duration-150 transition-colors p-ripple">
                        <i class="bi bi-search mr-2" />
                        <span>Saraksts</span>
                      </router-link>
                    </li>
                    <li v-if="can(`create instances`)">
                      <router-link :to="{ name: `CreateRegion` }" @click="visible = false" class="flex align-items-center cursor-pointer p-3 border-round hover:fw-bold transition-duration-150 transition-colors p-ripple">
                        <i class="bi bi-plus-lg mr-2" />
                        <span>Pievienošana</span>
                      </router-link>
                    </li>
                  </ul>
                </li>
                <Divider type="dotted" v-if="can(`manage countries`) || can(`manage regions`)"/>
                <li v-if="can(`manage instructors`)">
                  <div
                      v-styleclass="{
                                selector: '@next',
                                enterClass: 'hidden',
                                enterActiveClass: 'slidedown',
                                leaveToClass: 'hidden',
                                leaveActiveClass: 'slideup'
                            }"
                      class="p-3 flex align-items-center justify-content-between cursor-pointer p-ripple"
                  >
                    <span class="font-medium">TRENERI</span>
                    <i class="bi bi-chevron-down"></i>
                  </div>
                  <ul class="list-none py-0 pl-3 pr-0 m-0 hidden overflow-y-hidden transition-all transition-duration-400 transition-ease-in-out">
                    <li v-if="can(`list instances`)">
                      <router-link :to="{ name: `InstructorList` }" @click="visible = false" class="flex align-items-center cursor-pointer p-3 border-round hover:fw-bold transition-duration-150 transition-colors p-ripple">
                        <i class="bi bi-search mr-2" />
                        <span>Saraksts</span>
                      </router-link>
                    </li>
                    <li v-if="can(`create instances`)">
                      <router-link :to="{ name: `CreateInstructor` }" @click="visible = false" class="flex align-items-center cursor-pointer p-3 border-round hover:fw-bold transition-duration-150 transition-colors p-ripple">
                        <i class="bi bi-plus-lg mr-2" />
                        <span>Pievienošana</span>
                      </router-link>
                    </li>
                  </ul>
                </li>
                <li v-if="can(`manage certificates`)">
                  <div
                      v-styleclass="{
                                selector: '@next',
                                enterClass: 'hidden',
                                enterActiveClass: 'slidedown',
                                leaveToClass: 'hidden',
                                leaveActiveClass: 'slideup'
                            }"
                      class="p-3 flex align-items-center justify-content-between cursor-pointer p-ripple"
                  >
                    <span class="font-medium">SERTIFIKĀTI</span>
                    <i class="bi bi-chevron-down"></i>
                  </div>
                  <ul class="list-none py-0 pl-3 pr-0 m-0 hidden overflow-y-hidden transition-all transition-duration-400 transition-ease-in-out">
                    <li v-if="can(`list instances`)">
                      <router-link :to="{ name: `CertificateList` }" @click="visible = false" class="flex align-items-center cursor-pointer p-3 border-round hover:fw-bold transition-duration-150 transition-colors p-ripple">
                        <i class="bi bi-search mr-2" />
                        <span>Saraksts</span>
                      </router-link>
                    </li>
                    <li v-if="can(`create instances`)">
                      <router-link :to="{ name: `CreateCertificate` }" @click="visible = false" class="flex align-items-center cursor-pointer p-3 border-round hover:fw-bold transition-duration-150 transition-colors p-ripple">
                        <i class="bi bi-plus-lg mr-2" />
                        <span>Pievienošana</span>
                      </router-link>
                    </li>
                  </ul>
                </li>
                <Divider type="dotted" v-if="can(`manage instructors`) || can(`manage certificates`)" />
              </ul>
            </li>
          </ul>
        </div>
        <div class="mt-auto">
          <hr class="mb-1 mx-3 border-top-1 border-none border-white" />
          <div class="flex flex-column sm:flex-row align-items-center justify-content-between">
            <a class="mx-3 flex align-items-center cursor-pointer p-3 gap-2 transition-duration-150 transition-colors p-ripple" v-if="authStore.user">
              <Avatar shape="circle" style="background-color: #ffdfdf; color: #611212" :label="authStore.user?.name.substring(0, 1) + authStore.user?.surname.substring(0, 1)" />
              <span class="font-bold">{{ `${user.name} ${user.surname}` }}</span>
            </a>
            <div v-else />
            <div class="mb-2 sm:mr-3" :class="authStore.authorized ? `` : `my-2`">
              <Button v-if="!authStore.authorized" text class="bg-white shadow hover-black" rounded
                      @click="() => { $router.push({name: `LoginPage` }); visible = false; }">{{ $t(`navigation.login`) }}</Button>
              <Button v-if="authStore.authorized" text class="bg-white shadow hover-black" rounded
                      @click="() => { authStore.logout(); visible = false; }">{{ $t(`navigation.logout`) }}</Button>
            </div>
          </div>
        </div>
      </div>
    </template>
  </Sidebar>
</template>

<style scoped lang="scss">
.avatar {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  height: 35px;
  width: 35px;
  border-radius: 50%;
  font-size: 0.75em;
}

.p-sidebar {
  .submenu {
    font-family: "Ubuntu", sans-serif !important;
  }
}

.navbar a {
  font-weight: 900;
  text-transform: uppercase;
}
</style>