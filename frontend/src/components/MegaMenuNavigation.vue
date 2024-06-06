<template>
  <div class="navbar-overlap navbar navbar-container">
    <div class="container-xl">
      <Menubar :model="menuItems">
        <template #start>
          <svg
            class="h-2rem"
            fill="none"
            height="40"
            viewBox="0 0 35 40"
            width="35"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              d="M25.87 18.05L23.16 17.45L25.27 20.46V29.78L32.49 23.76V13.53L29.18 14.73L25.87 18.04V18.05ZM25.27 35.49L29.18 31.58V27.67L25.27 30.98V35.49ZM20.16 17.14H20.03H20.17H20.16ZM30.1 5.19L34.89 4.81L33.08 12.33L24.1 15.67L30.08 5.2L30.1 5.19ZM5.72 14.74L2.41 13.54V23.77L9.63 29.79V20.47L11.74 17.46L9.03 18.06L5.72 14.75V14.74ZM9.63 30.98L5.72 27.67V31.58L9.63 35.49V30.98ZM4.8 5.2L10.78 15.67L1.81 12.33L0 4.81L4.79 5.19L4.8 5.2ZM24.37 21.05V34.59L22.56 37.29L20.46 39.4H14.44L12.34 37.29L10.53 34.59V21.05L12.42 18.23L17.45 26.8L22.48 18.23L24.37 21.05ZM22.85 0L22.57 0.69L17.45 13.08L12.33 0.69L12.05 0H22.85Z"
              fill="var(--primary-color)"
            />
            <path
              d="M30.69 4.21L24.37 4.81L22.57 0.69L22.86 0H26.48L30.69 4.21ZM23.75 5.67L22.66 3.08L18.05 14.24V17.14H19.7H20.03H20.16H20.2L24.1 15.7L30.11 5.19L23.75 5.67ZM4.21002 4.21L10.53 4.81L12.33 0.69L12.05 0H8.43002L4.22002 4.21H4.21002ZM21.9 17.4L20.6 18.2H14.3L13 17.4L12.4 18.2L12.42 18.23L17.45 26.8L22.48 18.23L22.5 18.2L21.9 17.4ZM4.79002 5.19L10.8 15.7L14.7 17.14H14.74H15.2H16.85V14.24L12.24 3.09L11.15 5.68L4.79002 5.2V5.19Z"
              fill="var(--text-color)"
            />
          </svg>
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
