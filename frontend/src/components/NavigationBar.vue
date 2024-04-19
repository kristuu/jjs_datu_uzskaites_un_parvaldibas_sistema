<script setup>
import axios from "@/services/axios";
import { computed, ref } from "vue";
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
    label: locale.t("navigation.main.home"),
    command: () => {
      router.push({ name: "HomePage" });
    },
  },
]);

const changeLocale = async (newLocale) => {
  locale.value = newLocale;
  console.log(locale.value);
  try {
    await axios.post("/setlocale", { locale: newLocale });
    console.log(`success`);
  } catch (error) {
    await errorStore.displayError(error);
  }
};

let visible = ref(false);

const showSidebar = () => {
  visible.value = !visible.value;
};
</script>

<template>
  <div class="navbar navbar-overlap">
    <div class="container-xl">
      <Menubar
        :model="items"
        class="w-100 border-none align-items-center gap-2"
      >
        <template #start>
          <img
            class="h-2rem"
            src="@/assets/logo-white.svg"
            style="width: 50px; height: auto"
            @click="changeLocale"
          />
        </template>
        <template #menubutton><span /></template>
        <template #item="{ item, props, hasSubmenu, root }">
          <a class="flex" v-bind="props.action">
            <span v-if="item.icon" :class="item.icon" />
            <span :class="item.icon ? `ml-2` : ``">{{ item.label }}</span>
            <Badge
              v-if="item.badge"
              :class="{ 'ml-auto': !root, 'ml-2': root }"
              :value="item.badge"
            />
            <span
              v-if="item.shortcut"
              class="ml-auto border-1 surface-border surface-100 text-xs p-1"
              >{{ item.shortcut }}</span
            >
            <i
              v-if="hasSubmenu"
              :class="[
                'pi pi-angle-down',
                { 'pi-angle-down ml-2': root, 'pi-angle-right ml-auto': !root },
              ]"
            ></i>
          </a>
        </template>
        <template #end class="ml-5">
          <div class="flex align-items-center gap-2">
            <!--            <InputText :placeholder="$t('table.search')" type="text" class="w-8rem sm:w-auto" />-->
            <Button
              class="bg-white shadow hover-black text-primary"
              icon="bi bi-list-ul"
              rounded
              text
              @click="showSidebar"
            />
          </div>
        </template>
      </Menubar>
    </div>
  </div>

  <Sidebar
    v-model:visible="visible"
    class="w-100 border-0 text-white"
    position="right"
  >
    <template #container="{ closeCallback }">
      <div class="flex flex-column h-full yrs-bg-primary">
        <div
          class="flex align-items-center justify-content-between px-4 pt-3 flex-shrink-0"
        >
          <span class="inline-flex align-items-center gap-2">
            <a class="logo" href="/">
              <img
                class="h-2rem"
                src="@/assets/logo-white.svg"
                style="width: 50px; height: auto"
                @click="changeLocale"
              />
            </a>
          </span>
          <span>
            <Button
              class="h-2rem w-2rem text-white"
              icon="bi bi-x-lg"
              outlined
              rounded
              type="button"
              @click="closeCallback"
            ></Button>
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
                  leaveActiveClass: 'slideup',
                }"
                class="p-3 flex align-items-center justify-content-between cursor-pointer p-ripple"
              >
                <span class="submenu" style="font-weight: 900">{{
                  $t(`navigation.main.header`).toLocaleUpperCase()
                }}</span>
                <i class="bi bi-chevron-down"></i>
              </div>
              <ul class="list-none px-2 m-0 overflow-hidden">
                <li>
                  <router-link
                    v-ripple
                    :to="{ name: `HomePage` }"
                    class="flex align-items-center cursor-pointer p-3 border-round hover:fw-bold transition-duration-150 transition-colors p-ripple"
                    @click="visible = false"
                  >
                    <span class="font-medium">{{
                      $t(`navigation.main.home`).toLocaleUpperCase()
                    }}</span>
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
                  leaveActiveClass: 'slideup',
                }"
                class="p-3 flex align-items-center justify-content-between cursor-pointer p-ripple"
              >
                <span class="submenu" style="font-weight: 900">{{
                  $t(`navigation.admin.header`).toLocaleUpperCase()
                }}</span>
                <i class="bi bi-chevron-down"></i>
              </div>
              <ul class="list-none px-2 m-0 overflow-hidden">
                <li>
                  <router-link
                    :to="{ name: `AdminDashboard` }"
                    class="flex align-items-center cursor-pointer p-3 border-round hover:fw-bold transition-duration-150 transition-colors p-ripple"
                    @click="visible = false"
                  >
                    <span class="font-medium">{{
                      $t(`navigation.admin.home`).toLocaleUpperCase()
                    }}</span>
                  </router-link>
                </li>
                <Divider v-if="can(`access admin dashboard`)" type="dotted" />
                <li v-if="can(`manage users`)">
                  <div
                    v-styleclass="{
                      selector: '@next',
                      enterClass: 'hidden',
                      enterActiveClass: 'slidedown',
                      leaveToClass: 'hidden',
                      leaveActiveClass: 'slideup',
                    }"
                    class="p-3 flex align-items-center justify-content-between cursor-pointer p-ripple"
                  >
                    <span class="font-medium">{{
                      $t(`navigation.admin.users`).toLocaleUpperCase()
                    }}</span>
                    <i class="bi bi-chevron-down"></i>
                  </div>
                  <ul
                    class="list-none py-0 pl-3 pr-0 m-0 hidden overflow-y-hidden transition-all transition-duration-400 transition-ease-in-out"
                  >
                    <li v-if="can(`list instances`)">
                      <router-link
                        :to="{ name: `UserList` }"
                        class="flex align-items-center cursor-pointer p-3 border-round hover:fw-bold transition-duration-150 transition-colors p-ripple"
                        @click="visible = false"
                      >
                        <i class="bi bi-search mr-2" />
                        <span>{{ $t(`navigation.submenu.list`) }}</span>
                      </router-link>
                    </li>
                    <li v-if="can(`create instances`)">
                      <router-link
                        :to="{ name: `CreateUser` }"
                        class="flex align-items-center cursor-pointer p-3 border-round hover:fw-bold transition-duration-150 transition-colors p-ripple"
                        @click="visible = false"
                      >
                        <i class="bi bi-plus-lg mr-2" />
                        <span>{{ $t(`navigation.submenu.create`) }}</span>
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
                      leaveActiveClass: 'slideup',
                    }"
                    class="p-3 flex align-items-center justify-content-between cursor-pointer p-ripple"
                  >
                    <span class="font-medium">{{
                      $t(`navigation.admin.permissions`).toLocaleUpperCase()
                    }}</span>
                    <i class="bi bi-chevron-down"></i>
                  </div>
                  <ul
                    class="list-none py-0 pl-3 pr-0 m-0 hidden overflow-y-hidden transition-all transition-duration-400 transition-ease-in-out"
                  >
                    <li v-if="can(`list instances`)">
                      <router-link
                        :to="{ name: `PermissionList` }"
                        class="flex align-items-center cursor-pointer p-3 border-round hover:fw-bold transition-duration-150 transition-colors p-ripple"
                        @click="visible = false"
                      >
                        <i class="bi bi-search mr-2" />
                        <span>{{ $t(`navigation.submenu.list`) }}</span>
                      </router-link>
                    </li>
                    <li v-if="can(`create instances`)">
                      <router-link
                        :to="{ name: `CreatePermission` }"
                        class="flex align-items-center cursor-pointer p-3 border-round hover:fw-bold transition-duration-150 transition-colors p-ripple"
                        @click="visible = false"
                      >
                        <i class="bi bi-plus-lg mr-2" />
                        <span>{{ $t(`navigation.submenu.create`) }}</span>
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
                      leaveActiveClass: 'slideup',
                    }"
                    class="p-3 flex align-items-center justify-content-between cursor-pointer p-ripple"
                  >
                    <span class="font-medium">{{
                      $t(`navigation.admin.roles`).toLocaleUpperCase()
                    }}</span>
                    <i class="bi bi-chevron-down"></i>
                  </div>
                  <ul
                    class="list-none py-0 pl-3 pr-0 m-0 hidden overflow-y-hidden transition-all transition-duration-400 transition-ease-in-out"
                  >
                    <li v-if="can(`list instances`)">
                      <router-link
                        :to="{ name: `RoleList` }"
                        class="flex align-items-center cursor-pointer p-3 border-round hover:fw-bold transition-duration-150 transition-colors p-ripple"
                        @click="visible = false"
                      >
                        <i class="bi bi-search mr-2" />
                        <span>{{ $t(`navigation.submenu.list`) }}</span>
                      </router-link>
                    </li>
                    <li v-if="can(`create instances`)">
                      <router-link
                        :to="{ name: `CreateRole` }"
                        class="flex align-items-center cursor-pointer p-3 border-round hover:fw-bold transition-duration-150 transition-colors p-ripple"
                        @click="visible = false"
                      >
                        <i class="bi bi-plus-lg mr-2" />
                        <span>{{ $t(`navigation.submenu.create`) }}</span>
                      </router-link>
                    </li>
                  </ul>
                </li>
                <Divider
                  v-if="
                    can(`manage users`) ||
                    can(`manage permissions`) ||
                    can(`manage roles`)
                  "
                  type="dotted"
                />
                <li v-if="can(`manage countries`)">
                  <div
                    v-styleclass="{
                      selector: '@next',
                      enterClass: 'hidden',
                      enterActiveClass: 'slidedown',
                      leaveToClass: 'hidden',
                      leaveActiveClass: 'slideup',
                    }"
                    class="p-3 flex align-items-center justify-content-between cursor-pointer p-ripple"
                  >
                    <span class="font-medium">{{
                      $t(`navigation.admin.countries`).toLocaleUpperCase()
                    }}</span>
                    <i class="bi bi-chevron-down"></i>
                  </div>
                  <ul
                    class="list-none py-0 pl-3 pr-0 m-0 hidden overflow-y-hidden transition-all transition-duration-400 transition-ease-in-out"
                  >
                    <li v-if="can(`list instances`)">
                      <router-link
                        :to="{ name: `CountryList` }"
                        class="flex align-items-center cursor-pointer p-3 border-round hover:fw-bold transition-duration-150 transition-colors p-ripple"
                        @click="visible = false"
                      >
                        <i class="bi bi-search mr-2" />
                        <span>{{ $t(`navigation.submenu.list`) }}</span>
                      </router-link>
                    </li>
                    <li v-if="can(`create instances`)">
                      <router-link
                        :to="{ name: `CreateCountry` }"
                        class="flex align-items-center cursor-pointer p-3 border-round hover:fw-bold transition-duration-150 transition-colors p-ripple"
                        @click="visible = false"
                      >
                        <i class="bi bi-plus-lg mr-2" />
                        <span>{{ $t(`navigation.submenu.create`) }}</span>
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
                      leaveActiveClass: 'slideup',
                    }"
                    class="p-3 flex align-items-center justify-content-between cursor-pointer p-ripple"
                  >
                    <span class="font-medium">{{
                      $t(`navigation.admin.regions`).toLocaleUpperCase()
                    }}</span>
                    <i class="bi bi-chevron-down"></i>
                  </div>
                  <ul
                    class="list-none py-0 pl-3 pr-0 m-0 hidden overflow-y-hidden transition-all transition-duration-400 transition-ease-in-out"
                  >
                    <li v-if="can(`list instances`)">
                      <router-link
                        :to="{ name: `RegionList` }"
                        class="flex align-items-center cursor-pointer p-3 border-round hover:fw-bold transition-duration-150 transition-colors p-ripple"
                        @click="visible = false"
                      >
                        <i class="bi bi-search mr-2" />
                        <span>{{ $t(`navigation.submenu.list`) }}</span>
                      </router-link>
                    </li>
                    <li v-if="can(`create instances`)">
                      <router-link
                        :to="{ name: `CreateRegion` }"
                        class="flex align-items-center cursor-pointer p-3 border-round hover:fw-bold transition-duration-150 transition-colors p-ripple"
                        @click="visible = false"
                      >
                        <i class="bi bi-plus-lg mr-2" />
                        <span>{{ $t(`navigation.submenu.create`) }}</span>
                      </router-link>
                    </li>
                  </ul>
                </li>
                <Divider
                  v-if="can(`manage countries`) || can(`manage regions`)"
                  type="dotted"
                />
                <li v-if="can(`manage instructors`)">
                  <div
                    v-styleclass="{
                      selector: '@next',
                      enterClass: 'hidden',
                      enterActiveClass: 'slidedown',
                      leaveToClass: 'hidden',
                      leaveActiveClass: 'slideup',
                    }"
                    class="p-3 flex align-items-center justify-content-between cursor-pointer p-ripple"
                  >
                    <span class="font-medium">{{
                      $t(`navigation.admin.instructors`).toLocaleUpperCase()
                    }}</span>
                    <i class="bi bi-chevron-down"></i>
                  </div>
                  <ul
                    class="list-none py-0 pl-3 pr-0 m-0 hidden overflow-y-hidden transition-all transition-duration-400 transition-ease-in-out"
                  >
                    <li v-if="can(`list instances`)">
                      <router-link
                        :to="{ name: `InstructorList` }"
                        class="flex align-items-center cursor-pointer p-3 border-round hover:fw-bold transition-duration-150 transition-colors p-ripple"
                        @click="visible = false"
                      >
                        <i class="bi bi-search mr-2" />
                        <span>{{ $t(`navigation.submenu.list`) }}</span>
                      </router-link>
                    </li>
                    <li v-if="can(`create instances`)">
                      <router-link
                        :to="{ name: `CreateInstructor` }"
                        class="flex align-items-center cursor-pointer p-3 border-round hover:fw-bold transition-duration-150 transition-colors p-ripple"
                        @click="visible = false"
                      >
                        <i class="bi bi-plus-lg mr-2" />
                        <span>{{ $t(`navigation.submenu.create`) }}</span>
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
                      leaveActiveClass: 'slideup',
                    }"
                    class="p-3 flex align-items-center justify-content-between cursor-pointer p-ripple"
                  >
                    <span class="font-medium">{{
                      $t(`navigation.admin.certificates`).toLocaleUpperCase()
                    }}</span>
                    <i class="bi bi-chevron-down"></i>
                  </div>
                  <ul
                    class="list-none py-0 pl-3 pr-0 m-0 hidden overflow-y-hidden transition-all transition-duration-400 transition-ease-in-out"
                  >
                    <li v-if="can(`list instances`)">
                      <router-link
                        :to="{ name: `CertificateList` }"
                        class="flex align-items-center cursor-pointer p-3 border-round hover:fw-bold transition-duration-150 transition-colors p-ripple"
                        @click="visible = false"
                      >
                        <i class="bi bi-search mr-2" />
                        <span>{{ $t(`navigation.submenu.list`) }}</span>
                      </router-link>
                    </li>
                    <li v-if="can(`create instances`)">
                      <router-link
                        :to="{ name: `CreateCertificate` }"
                        class="flex align-items-center cursor-pointer p-3 border-round hover:fw-bold transition-duration-150 transition-colors p-ripple"
                        @click="visible = false"
                      >
                        <i class="bi bi-plus-lg mr-2" />
                        <span>{{ $t(`navigation.submenu.create`) }}</span>
                      </router-link>
                    </li>
                  </ul>
                </li>
                <li v-if="can(`manage categories`)">
                  <div
                    v-styleclass="{
                      selector: '@next',
                      enterClass: 'hidden',
                      enterActiveClass: 'slidedown',
                      leaveToClass: 'hidden',
                      leaveActiveClass: 'slideup',
                    }"
                    class="p-3 flex align-items-center justify-content-between cursor-pointer p-ripple"
                  >
                    <span class="font-medium">{{
                      $t(`navigation.admin.categories`).toLocaleUpperCase()
                    }}</span>
                    <i class="bi bi-chevron-down"></i>
                  </div>
                  <ul
                    class="list-none py-0 pl-3 pr-0 m-0 hidden overflow-y-hidden transition-all transition-duration-400 transition-ease-in-out"
                  >
                    <li v-if="can(`list instances`)">
                      <router-link
                        :to="{ name: `CategoryList` }"
                        class="flex align-items-center cursor-pointer p-3 border-round hover:fw-bold transition-duration-150 transition-colors p-ripple"
                        @click="visible = false"
                      >
                        <i class="bi bi-search mr-2" />
                        <span>{{ $t(`navigation.submenu.list`) }}</span>
                      </router-link>
                    </li>
                    <li v-if="can(`create instances`)">
                      <router-link
                        :to="{ name: `CreateCategory` }"
                        class="flex align-items-center cursor-pointer p-3 border-round hover:fw-bold transition-duration-150 transition-colors p-ripple"
                        @click="visible = false"
                      >
                        <i class="bi bi-plus-lg mr-2" />
                        <span>{{ $t(`navigation.submenu.create`) }}</span>
                      </router-link>
                    </li>
                  </ul>
                </li>
                <Divider
                  v-if="
                    can(`manage instructors`) ||
                    can(`manage certificates`) ||
                    can(`manage categories`)
                  "
                  type="dotted"
                />
                <li v-if="can(`manage events`)">
                  <div
                    v-styleclass="{
                      selector: '@next',
                      enterClass: 'hidden',
                      enterActiveClass: 'slidedown',
                      leaveToClass: 'hidden',
                      leaveActiveClass: 'slideup',
                    }"
                    class="p-3 flex align-items-center justify-content-between cursor-pointer p-ripple"
                  >
                    <span class="font-medium">{{
                      $t(`navigation.admin.events`).toLocaleUpperCase()
                    }}</span>
                    <i class="bi bi-chevron-down"></i>
                  </div>
                  <ul
                    class="list-none py-0 pl-3 pr-0 m-0 hidden overflow-y-hidden transition-all transition-duration-400 transition-ease-in-out"
                  >
                    <li v-if="can(`list instances`)">
                      <router-link
                        :to="{ name: `EventList` }"
                        class="flex align-items-center cursor-pointer p-3 border-round hover:fw-bold transition-duration-150 transition-colors p-ripple"
                        @click="visible = false"
                      >
                        <i class="bi bi-search mr-2" />
                        <span>{{ $t(`navigation.submenu.list`) }}</span>
                      </router-link>
                    </li>
                    <li v-if="can(`create instances`)">
                      <router-link
                        :to="{ name: `CreateEvent` }"
                        class="flex align-items-center cursor-pointer p-3 border-round hover:fw-bold transition-duration-150 transition-colors p-ripple"
                        @click="visible = false"
                      >
                        <i class="bi bi-plus-lg mr-2" />
                        <span>{{ $t(`navigation.submenu.create`) }}</span>
                      </router-link>
                    </li>
                  </ul>
                </li>
                <li v-if="can(`manage event_categories`)">
                  <div
                    v-styleclass="{
                      selector: '@next',
                      enterClass: 'hidden',
                      enterActiveClass: 'slidedown',
                      leaveToClass: 'hidden',
                      leaveActiveClass: 'slideup',
                    }"
                    class="p-3 flex align-items-center justify-content-between cursor-pointer p-ripple"
                  >
                    <span class="font-medium">{{
                      $t(
                        `navigation.admin.event_categories`
                      ).toLocaleUpperCase()
                    }}</span>
                    <i class="bi bi-chevron-down"></i>
                  </div>
                  <ul
                    class="list-none py-0 pl-3 pr-0 m-0 hidden overflow-y-hidden transition-all transition-duration-400 transition-ease-in-out"
                  >
                    <li v-if="can(`list instances`)">
                      <router-link
                        :to="{ name: `EventCategoryList` }"
                        class="flex align-items-center cursor-pointer p-3 border-round hover:fw-bold transition-duration-150 transition-colors p-ripple"
                        @click="visible = false"
                      >
                        <i class="bi bi-search mr-2" />
                        <span>{{ $t(`navigation.submenu.list`) }}</span>
                      </router-link>
                    </li>
                    <li v-if="can(`create instances`)">
                      <router-link
                        :to="{ name: `CreateEventCategory` }"
                        class="flex align-items-center cursor-pointer p-3 border-round hover:fw-bold transition-duration-150 transition-colors p-ripple"
                        @click="visible = false"
                      >
                        <i class="bi bi-plus-lg mr-2" />
                        <span>{{ $t(`navigation.submenu.create`) }}</span>
                      </router-link>
                    </li>
                  </ul>
                </li>
                <li v-if="can(`manage event_types`)">
                  <div
                    v-styleclass="{
                      selector: '@next',
                      enterClass: 'hidden',
                      enterActiveClass: 'slidedown',
                      leaveToClass: 'hidden',
                      leaveActiveClass: 'slideup',
                    }"
                    class="p-3 flex align-items-center justify-content-between cursor-pointer p-ripple"
                  >
                    <span class="font-medium">{{
                      $t(`navigation.admin.event_types`).toLocaleUpperCase()
                    }}</span>
                    <i class="bi bi-chevron-down"></i>
                  </div>
                  <ul
                    class="list-none py-0 pl-3 pr-0 m-0 hidden overflow-y-hidden transition-all transition-duration-400 transition-ease-in-out"
                  >
                    <li v-if="can(`list instances`)">
                      <router-link
                        :to="{ name: `EventTypeList` }"
                        class="flex align-items-center cursor-pointer p-3 border-round hover:fw-bold transition-duration-150 transition-colors p-ripple"
                        @click="visible = false"
                      >
                        <i class="bi bi-search mr-2" />
                        <span>{{ $t(`navigation.submenu.list`) }}</span>
                      </router-link>
                    </li>
                    <li v-if="can(`create instances`)">
                      <router-link
                        :to="{ name: `CreateEventType` }"
                        class="flex align-items-center cursor-pointer p-3 border-round hover:fw-bold transition-duration-150 transition-colors p-ripple"
                        @click="visible = false"
                      >
                        <i class="bi bi-plus-lg mr-2" />
                        <span>{{ $t(`navigation.submenu.create`) }}</span>
                      </router-link>
                    </li>
                  </ul>
                </li>
                <Divider
                  v-if="
                    can(`manage events`) ||
                    can(`manage event_types`) ||
                    can(`manage event_categories`)
                  "
                  type="dotted"
                />
              </ul>
            </li>
          </ul>
        </div>
        <div class="mt-auto">
          <hr class="mb-1 mx-3 border-top-1 border-none border-white" />
          <div
            class="flex flex-column sm:flex-row align-items-center justify-content-between"
          >
            <a
              v-if="authStore.user"
              class="mx-3 flex align-items-center cursor-pointer p-3 gap-2 transition-duration-150 transition-colors p-ripple"
            >
              <Avatar
                :label="
                  authStore.user?.name.substring(0, 1) +
                  authStore.user?.surname.substring(0, 1)
                "
                shape="circle"
                style="background-color: #ffdfdf; color: #611212"
              />
              <span class="font-bold">{{
                `${user.name} ${user.surname}`
              }}</span>
            </a>
            <div v-else />
            <div
              :class="authStore.authorized ? `` : `my-2`"
              class="mb-2 sm:mr-3"
            >
              <Button
                v-if="!authStore.authorized"
                class="bg-white shadow hover-black"
                rounded
                text
                @click="
                  () => {
                    $router.push({ name: `LoginPage` });
                    visible = false;
                  }
                "
                >{{ $t(`navigation.login`) }}
              </Button>
              <Button
                v-if="authStore.authorized"
                class="bg-white shadow hover-black"
                rounded
                text
                @click="
                  () => {
                    authStore.logout();
                    visible = false;
                  }
                "
                >{{ $t(`navigation.logout`) }}
              </Button>
            </div>
          </div>
        </div>
      </div>
    </template>
  </Sidebar>
</template>

<style lang="scss" scoped>
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
