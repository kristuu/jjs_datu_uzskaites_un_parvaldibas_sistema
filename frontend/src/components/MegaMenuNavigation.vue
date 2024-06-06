<template>
  <div class="navbar-overlap navbar navbar-container">
    <div class="container-xl">
      <Menubar :model="menuItems">
        <template #start>
          <img
            class="h-2rem"
            src="@/assets/logo-white.svg"
            style="width: 50px; height: auto"
            @click="changeLocale"
          />
        </template>
        <template #item="{ item, props, hasSubmenu, root }">
          <a
            v-ripple
            class="flex align-items-center h-full"
            v-bind="props.action"
          >
            <span :class="item.icon" />
            <span :class="item.icon ? 'ml-2 ' : '' + item.class">{{
              item.label
            }}</span>
            <Badge
              v-if="item.badge"
              :class="{ 'ml-auto': !root, 'ml-2': root }"
              :value="item.badge"
            />
            <span
              v-if="item.shortcut"
              class="ml-auto border-1 surface-border border-round surface-100 text-xs p-1"
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
        <template #end>
          <div class="flex align-items-center gap-2">
            <!--            <InputText-->
            <!--              class="w-8rem sm:w-auto"-->
            <!--              placeholder="Search"-->
            <!--              type="text"-->
            <!--            />-->
            <Avatar
              :image="authStore.user?.profile_picture"
              class="cursor-pointer"
              shape="circle"
              @click="$router.push({ name: `UserProfile` })"
            />
          </div>
        </template>
      </Menubar>
    </div>
  </div>
</template>

<script setup>
import { useRouter } from "vue-router";
import { useI18n } from "vue-i18n";
import { useAuthStore } from "@/stores/authStore";
import { ref } from "vue";

const router = useRouter();
const { t } = useI18n();
const authStore = useAuthStore();

const menuItems = ref([
  {
    label: t("navigation.main.home").toLocaleUpperCase(),
    class: "fw-bold",
    command: () => router.push({ name: "HomePage" }),
  },
  {
    label: t("navigation.admin.header").toLocaleUpperCase(),
    class: "fw-bold",
    items: [
      {
        label: t("navigation.admin.home"),
        icon: "bi bi-house-gear-fill",
        class: "fw-normal",
        command: () => router.push({ name: "AdminDashboard" }),
      },
      {
        label: t("navigation.admin.access"),
        icon: "bi bi-people-fill",
        class: "fw-normal",
        items: [
          {
            label: t("navigation.admin.users"),
            icon: "pi pi-users",
            command: () => router.push({ name: "UserList" }),
          },
          {
            label: t("navigation.admin.permissions"),
            icon: "pi pi-key",
            command: () => router.push({ name: "PermissionList" }),
          },
          {
            label: t("navigation.admin.roles"),
            icon: "pi pi-briefcase",
            command: () => router.push({ name: "RoleList" }),
          },
        ],
      },
      {
        label: t("navigation.admin.geolocation"),
        icon: "bi bi-compass-fill",
        class: "fw-normal",
        items: [
          {
            label: t("navigation.admin.countries"),
            icon: "pi pi-globe",
            command: () => router.push({ name: "CountryList" }),
          },
          {
            label: t("navigation.admin.regions"),
            icon: "pi pi-map",
            command: () => router.push({ name: "RegionList" }),
          },
        ],
      },
      {
        label: t("navigation.admin.trainings"),
        icon: "bi bi-suitcase-lg-fill",
        class: "fw-normal",
        items: [
          {
            label: t("navigation.admin.instructors"),
            icon: "bi bi-person-badge",
            command: () => router.push({ name: "InstructorList" }),
          },
          {
            label: t("navigation.admin.certificates"),
            icon: "bi bi-patch-check",
            command: () => router.push({ name: "CertificateList" }),
          },
          {
            label: t("navigation.admin.categories"),
            icon: "bi bi-tags",
            command: () => router.push({ name: "CategoryList" }),
          },
        ],
      },
      {
        label: t("navigation.admin.events"),
        icon: "bi bi-calendar-event-fill",
        class: "fw-normal",
        items: [
          {
            label: t("navigation.admin.events"),
            icon: "pi pi-calendar",
            command: () => router.push({ name: "EventList" }),
          },
          {
            label: t("navigation.admin.event_categories"),
            icon: "pi pi-sitemap",
            command: () => router.push({ name: "EventCategoryList" }),
          },
          {
            label: t("navigation.admin.event_types"),
            icon: "pi pi-sliders-h",
            command: () => router.push({ name: "EventTypeList" }),
          },
          {
            label: t("navigation.admin.locations"),
            icon: "pi pi-map-marker",
            command: () => router.push({ name: "LocationList" }),
          },
        ],
      },
    ],
  },
]);
</script>

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
