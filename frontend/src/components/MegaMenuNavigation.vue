<template>
  <div class="navbar-overlap navbar navbar-container">
    <div class="container-xl">
      <Menubar :model="computedMenuItems">
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
            v-if="item.visible !== false"
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
          <div v-if="authStore.user" class="flex align-items-center gap-2">
            <Avatar
              :image="authStore.user?.profile_picture"
              class="cursor-pointer"
              shape="circle"
              @click="$router.push({ name: `UserProfile` })"
            />
            <Button
              class="bg-white text-primary hover-black"
              label="IZIET"
              rounded
              @click="authStore.logout()"
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
import { computed, ref, watch } from "vue";

const router = useRouter();
const { t } = useI18n();
const authStore = useAuthStore();

const hasPermission = (permission) => {
  return authStore.permissions?.includes(permission) ?? false;
};

const hasRole = (role) => {
  return authStore.roles?.includes(role) ?? false;
};

const categories = computed(() => authStore.user?.categories ?? []);

const getMenuItems = () => {
  return [
    {
      label: t("navigation.main.home").toLocaleUpperCase(),
      class: "fw-bold",
      command: () => router.push({ name: "HomePage" }),
    },
    {
      label: `Trenera profils`.toLocaleUpperCase(),
      class: "fw-bold",
      visible:
        categories.value.length > 0 &&
        (hasRole("dressage instructor") || hasRole("show jumping instructor")),
      items:
        categories.value.length > 0
          ? categories.value.map((category) => ({
              label: category.name,
              class: "fw-normal",
              command: () =>
                router.push({
                  name: "InstructorProfile",
                  params: { id: category.instructor_id },
                }),
            }))
          : [{ label: t("navigation.loading"), disabled: true }],
    },
    {
      label: t("navigation.admin.header").toLocaleUpperCase(),
      class: "fw-bold",
      visible: hasPermission("access admin dashboard"),
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
          visible:
            hasPermission("manage users") ||
            hasPermission("manage permissions") ||
            hasPermission("manage roles"),
          items: [
            {
              label: t("navigation.admin.users"),
              icon: "pi pi-users",
              command: () => router.push({ name: "UserList" }),
              visible: hasPermission("manage users"),
            },
            {
              label: t("navigation.admin.permissions"),
              icon: "pi pi-key",
              command: () => router.push({ name: "PermissionList" }),
              visible: hasPermission("manage permissions"),
            },
            {
              label: t("navigation.admin.roles"),
              icon: "pi pi-briefcase",
              command: () => router.push({ name: "RoleList" }),
              visible: hasPermission("manage roles"),
            },
          ],
        },
        {
          label: t("navigation.admin.geolocation"),
          icon: "bi bi-compass-fill",
          class: "fw-normal",
          visible:
            hasPermission("manage countries") || hasPermission("manage cities"),
          items: [
            {
              label: t("navigation.admin.countries"),
              icon: "pi pi-globe",
              command: () => router.push({ name: "CountryList" }),
              visible: hasPermission("manage countries"),
            },
            {
              label: t("navigation.admin.cities"),
              icon: "pi pi-map",
              command: () => router.push({ name: "CityList" }),
              visible: hasPermission("manage cities"),
            },
          ],
        },
        {
          label: t("navigation.admin.trainings"),
          icon: "bi bi-suitcase-lg-fill",
          class: "fw-normal",
          visible:
            hasPermission("manage instructors") ||
            hasPermission("manage certificates") ||
            hasPermission("manage categories"),
          items: [
            {
              label: t("navigation.admin.instructors"),
              icon: "bi bi-person-badge",
              command: () => router.push({ name: "InstructorList" }),
              visible: hasPermission("manage instructors"),
            },
            {
              label: t("navigation.admin.certificates"),
              icon: "bi bi-patch-check",
              command: () => router.push({ name: "CertificateList" }),
              visible: hasPermission("manage certificates"),
            },
            {
              label: t("navigation.admin.categories"),
              icon: "bi bi-tags",
              command: () => router.push({ name: "CategoryList" }),
              visible: hasPermission("manage categories"),
            },
          ],
        },
        {
          label: t("navigation.admin.events"),
          icon: "bi bi-calendar-event-fill",
          class: "fw-normal",
          visible:
            hasPermission("manage events") ||
            hasPermission("manage event categories") ||
            hasPermission("manage event types") ||
            hasPermission("manage locations"),
          items: [
            {
              label: t("navigation.admin.events"),
              icon: "pi pi-calendar",
              command: () => router.push({ name: "EventList" }),
              visible: hasPermission("manage events"),
            },
            {
              label: t("navigation.admin.event_categories"),
              icon: "pi pi-sitemap",
              command: () => router.push({ name: "EventCategoryList" }),
              visible: hasPermission("manage event categories"),
            },
            {
              label: t("navigation.admin.event_types"),
              icon: "pi pi-sliders-h",
              command: () => router.push({ name: "EventTypeList" }),
              visible: hasPermission("manage event types"),
            },
            {
              label: t("navigation.admin.locations"),
              icon: "pi pi-map-marker",
              command: () => router.push({ name: "LocationList" }),
              visible: hasPermission("manage locations"),
            },
          ],
        },
      ].filter((item) => item.visible !== false),
    },
  ];
};

const menuItems = ref(getMenuItems());

watch(
  () => [authStore.permissions, authStore.roles, categories.value],
  () => {
    menuItems.value = getMenuItems();
  },
  { deep: true }
);

const computedMenuItems = computed(() => menuItems.value);
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
