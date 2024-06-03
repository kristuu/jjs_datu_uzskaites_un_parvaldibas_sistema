import { createRouter, createWebHistory } from "vue-router";

import HomePage from "../views/HomePage.vue";
import UserList from "@/views/user/UserList.vue";
import CreateUser from "../views/user/CreateUser.vue";
import EditUser from "../views/user/EditUser.vue";
import LoginPage from "../views/auth/LoginPage.vue";
import RegisterPage from "../views/auth/RegisterPage.vue";

import MaugloCompute from "@/views/calculateReportDiapazone.vue";
import ErrorView from "@/views/error/ErrorView.vue";
import PermissionList from "@/views/permission/PermissionList.vue";
import CreatePermission from "@/views/permission/CreatePermission.vue";
import EditPermission from "@/views/permission/EditPermission.vue";
import RoleList from "@/views/role/RoleList.vue";
import CreateRole from "@/views/role/CreateRole.vue";
import EditRole from "@/views/role/EditRole.vue";
import CountryList from "@/views/country/CountryList.vue";
import CreateCountry from "@/views/country/CreateCountry.vue";
import EditCountry from "@/views/country/EditCountry.vue";
import RegionList from "@/views/region/RegionList.vue";
import CreateRegion from "@/views/region/CreateRegion.vue";
import EditRegion from "@/views/region/EditRegion.vue";
import InstructorList from "@/views/instructor/InstructorList.vue";
import CreateInstructor from "@/views/instructor/CreateInstructor.vue";
import EditInstructor from "@/views/instructor/EditInstructor.vue";

import { useFetchDataStore } from "@/stores/fetchDataStore";
import { useErrorStore } from "@/stores/errorStore";
import { useAuthStore } from "@/stores/authStore";
import CertificateList from "@/views/certificate/CertificateList.vue";
import CreateCertificate from "@/views/certificate/CreateCertificate.vue";
import EditCertificate from "@/views/certificate/EditCertificate.vue";
import CategoryList from "@/views/category/CategoryList.vue";
import CreateCategory from "@/views/category/CreateCategory.vue";
import EditCategory from "@/views/category/EditCategory.vue";
import Container from "@/views/admin/Container.vue";
import Dashboard from "@/views/admin/Dashboard.vue";
import EventList from "@/views/event/EventList.vue";
import CreateEvent from "@/views/event/CreateEvent.vue";
import EditEvent from "@/views/event/EditEvent.vue";
import EventCategoryList from "@/views/eventCategory/EventCategoryList.vue";
import CreateEventCategory from "@/views/eventCategory/CreateEventCategory.vue";
import EditEventCategory from "@/views/eventCategory/EditEventCategory.vue";
import EventTypeList from "@/views/eventType/EventTypeList.vue";
import CreateEventType from "@/views/eventType/CreateEventType.vue";
import EditEventType from "@/views/eventType/EditEventType.vue";
import LocationList from "@/views/location/LocationList.vue";
import CreateLocation from "@/views/location/CreateLocation.vue";
import EditLocation from "@/views/location/EditLocation.vue";
import BookingList from "@/views/booking/BookingList.vue";
import BookingInstructor from "@/views/booking/BookingInstructor.vue";
import UserProfileEdit from "@/views/public/user/UserProfileEdit.vue";

const routes = [
  {
    path: "/",
    name: "HomePage",
    component: HomePage,
    meta: { public: true },
  },
  {
    path: "/booking",
    name: "BookingList",
    component: BookingList,
    meta: { public: false },
  },
  {
    path: "/booking/:id",
    name: "BookingInstructor",
    component: BookingInstructor,
    meta: { public: false },
  },
  {
    path: "/login",
    name: "LoginPage",
    component: LoginPage,
    meta: { public: true },
  },
  {
    path: "/register",
    name: "RegisterPage",
    component: RegisterPage,
    meta: { public: true },
  },
  {
    path: "/mauglo",
    name: "MaugloCompute",
    component: MaugloCompute,
    meta: { public: false },
  },
  {
    path: "/error",
    name: "ErrorView",
    component: ErrorView,
    meta: { public: true },
  },
  {
    path: "/profile",
    name: "UserProfile",
    component: UserProfileEdit,
    meta: { public: false },
  },
  {
    path: "/admin",
    component: Container,
    children: [
      {
        path: "",
        name: "AdminDashboard",
        component: Dashboard,
        meta: { public: false },
      },

      {
        path: "users",
        name: "UserList",
        component: UserList,
        meta: { public: false },
      },
      {
        path: "users/:id/edit",
        name: "EditUser",
        component: EditUser,
        meta: { public: false },
      },
      {
        path: "users/create",
        name: "CreateUser",
        component: CreateUser,
        meta: { public: false },
      },

      {
        path: "permissions",
        name: "PermissionList",
        component: PermissionList,
        meta: { public: false },
      },
      {
        path: "permissions/create",
        name: "CreatePermission",
        component: CreatePermission,
        meta: { public: false },
      },
      {
        path: "permissions/:id/edit",
        name: "EditPermission",
        component: EditPermission,
        meta: { public: false },
      },

      {
        path: "roles",
        name: "RoleList",
        component: RoleList,
        meta: { public: false },
      },
      {
        path: "roles/create",
        name: "CreateRole",
        component: CreateRole,
        meta: { public: false },
      },
      {
        path: "roles/:id/edit",
        name: "EditRole",
        component: EditRole,
        meta: { public: false },
      },

      {
        path: "countries",
        name: "CountryList",
        component: CountryList,
        meta: { public: false },
      },
      {
        path: "countries/create",
        name: "CreateCountry",
        component: CreateCountry,
        meta: { public: false },
      },
      {
        path: "countries/:id/edit",
        name: "EditCountry",
        component: EditCountry,
        meta: { public: false },
      },

      {
        path: "regions",
        name: "RegionList",
        component: RegionList,
        meta: { public: false },
      },
      {
        path: "regions/create",
        name: "CreateRegion",
        component: CreateRegion,
        meta: { public: false },
      },
      {
        path: "regions/:id/edit",
        name: "EditRegion",
        component: EditRegion,
        meta: { public: false },
      },

      {
        path: "instructors",
        name: "InstructorList",
        component: InstructorList,
        meta: { public: false },
      },
      {
        path: "instructors/create",
        name: "CreateInstructor",
        component: CreateInstructor,
        meta: { public: false },
      },
      {
        path: "instructors/:id/edit",
        name: "EditInstructor",
        component: EditInstructor,
        meta: { public: false },
      },

      {
        path: "certificates",
        name: "CertificateList",
        component: CertificateList,
        meta: { public: false },
      },
      {
        path: "certificates/create",
        name: "CreateCertificate",
        component: CreateCertificate,
        meta: { public: false },
      },
      {
        path: "certificates/:id/edit",
        name: "EditCertificate",
        component: EditCertificate,
        meta: { public: false },
      },

      {
        path: "categories",
        name: "CategoryList",
        component: CategoryList,
        meta: { public: false },
      },
      {
        path: "categories/create",
        name: "CreateCategory",
        component: CreateCategory,
        meta: { public: false },
      },
      {
        path: "categories/:id/edit",
        name: "EditCategory",
        component: EditCategory,
        meta: { public: false },
      },

      {
        path: "events",
        name: "EventList",
        component: EventList,
        meta: { public: false },
      },
      {
        path: "events/create",
        name: "CreateEvent",
        component: CreateEvent,
        meta: { public: false },
      },
      {
        path: "events/:id/edit",
        name: "EditEvent",
        component: EditEvent,
        meta: { public: false },
      },

      {
        path: "event_categories",
        name: "EventCategoryList",
        component: EventCategoryList,
        meta: { public: false },
      },
      {
        path: "event_categories/create",
        name: "CreateEventCategory",
        component: CreateEventCategory,
        meta: { public: false },
      },
      {
        path: "event_categories/:id/edit",
        name: "EditEventCategory",
        component: EditEventCategory,
        meta: { public: false },
      },

      {
        path: "event_types",
        name: "EventTypeList",
        component: EventTypeList,
        meta: { public: false },
      },
      {
        path: "event_types/create",
        name: "CreateEventType",
        component: CreateEventType,
        meta: { public: false },
      },
      {
        path: "event_types/:id/edit",
        name: "EditEventType",
        component: EditEventType,
        meta: { public: false },
      },

      {
        path: "locations",
        name: "LocationList",
        component: LocationList,
        meta: { public: false },
      },
      {
        path: "locations/create",
        name: "CreateLocation",
        component: CreateLocation,
        meta: { public: false },
      },
      {
        path: "locations/:id/edit",
        name: "EditLocation",
        component: EditLocation,
        meta: { public: false },
      },
    ],
  },
  {
    path: "/:pathMatch(.*)*",
    component: ErrorView,
  },
];

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes,
});

router.beforeEach((to, from, next) => {
  const fetchDataStore = useFetchDataStore();
  fetchDataStore.resetAllInstances();
  fetchDataStore.resetInstance();
  fetchDataStore
    .hideComponents()
    .then(() => {
      useErrorStore().resetErrorList();
      useErrorStore().resetError();
      if (to.meta.public) {
        next();
      } else {
        const authStore = useAuthStore();
        authStore
          .checkAuth()
          .then(() => {
            if (authStore.authorized) {
              next();
            } else {
              next({ name: "LoginPage" });
            }
          })
          .catch((err) => {
            // this catch will handle any error occurred in the checkAuth promise
            console.error(err);
            next({ name: "LoginPage" });
          });
      }
    })
    .catch((err) => {
      // this catch will handle any error occured in hideComponents promise
      console.error(err);
      next();
    });
});

export default router;
