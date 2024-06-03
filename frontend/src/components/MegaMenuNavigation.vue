<template>
  <div class="navbar-overlap navbar navbar-container">
    <div class="container-xl">
      <MegaMenu
        :model="menuItems"
        class="p-3 surface-0 megamenu-custom"
        style="border-radius: 3rem"
      >
        <template #start>
          <img
            class="h-2rem"
            src="@/assets/logo-white.svg"
            style="width: 50px; height: auto"
            @click="changeLocale"
          />
        </template>
        <template #item="{ item }">
          <a
            v-if="item.root"
            v-ripple
            class="flex align-items-center cursor-pointer px-3 py-2 overflow-hidden relative font-semibold text-lg uppercase ml-2"
            style="border-radius: 2rem"
          >
            <span :class="item.icon" class="focus-color-primary" />
            <span class="focus-color-primary fw-bold">{{ item.label }}</span>
          </a>
          <a
            v-else-if="!item.image"
            class="flex align-items-center p-3 cursor-pointer mb-2 gap-2"
          >
            <span
              class="inline-flex align-items-center justify-content-center border-circle bg-primary w-3rem h-3rem"
            >
              <i :class="[item.icon, 'text-lg']"></i>
            </span>
            <span class="inline-flex flex-column gap-1">
              <span class="font-medium text-lg text-900">{{ item.label }}</span>
              <span class="white-space-nowrap">{{ item.subtext }}</span>
            </span>
          </a>
          <div v-else class="flex flex-column align-items-start gap-3">
            <img :src="item.image" alt="megamenu-demo" class="w-full" />
            <span>{{ item.subtext }}</span>
            <Button :label="item.label" outlined />
          </div>
        </template>
        <template #end>
          <RouterLink :to="{ name: 'UserProfile' }">
            <Avatar :image="authStore.user?.profile_picture" shape="circle" />
          </RouterLink>
        </template>
      </MegaMenu>
    </div>
  </div>
</template>

<script setup>
import { computed } from "vue";
import { useRouter } from "vue-router";
import { useI18n } from "vue-i18n";
import { useAuthStore } from "@/stores/authStore";

const router = useRouter();
const { t } = useI18n();
const authStore = useAuthStore();

const menuItems = computed(() => [
  {
    label: t("navigation.main.home"),
    root: true,
    command: () => router.push({ name: "HomePage" }),
  },
  {
    label: t("navigation.admin.header"),
    root: true,
    items: [
      [
        {
          items: [
            {
              label: t("navigation.admin.home"),
              icon: "pi pi-home",
              command: () => router.push({ name: "AdminDashboard" }),
            },
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
      ],
      [
        {
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
      ],
      [
        {
          items: [
            {
              label: t("navigation.admin.instructors"),
              icon: "pi pi-book",
              command: () => router.push({ name: "InstructorList" }),
            },
            {
              label: t("navigation.admin.certificates"),
              icon: "pi pi-certificate",
              command: () => router.push({ name: "CertificateList" }),
            },
            {
              label: t("navigation.admin.categories"),
              icon: "pi pi-tags",
              command: () => router.push({ name: "CategoryList" }),
            },
          ],
        },
      ],
      [
        {
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
    ],
  },
]);
</script>

<style scoped>
.p-megamenu {
  background: none !important;
  border: none !important;
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

.navbar a {
  font-weight: 900;
  text-transform: uppercase;
}
</style>
