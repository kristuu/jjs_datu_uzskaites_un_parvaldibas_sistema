import { createRouter, createWebHistory } from "vue-router";

import HomePage from "../views/HomePage.vue";
import UserList from "@/views/admin/user/UserList.vue";
import CreateUser from "../views/admin/user/CreateUser.vue";
import EditUser from "../views/admin/user/EditUser.vue";
import LoginPage from "../views/auth/LoginPage.vue";
import RegisterPage from "../views/auth/RegisterPage.vue";

import MaugloCompute from "@/views/admin/calculateReportDiapazone.vue";
import ErrorView from "@/views/error/ErrorView.vue";
import PermissionList from "@/views/admin/permission/PermissionList.vue";
import CreatePermission from "@/views/admin/permission/CreatePermission.vue";
import EditPermission from "@/views/admin/permission/EditPermission.vue";
import RoleList from "@/views/admin/role/RoleList.vue";
import CreateRole from "@/views/admin/role/CreateRole.vue";
import EditRole from "@/views/admin/role/EditRole.vue";
import CountryList from "@/views/admin/country/CountryList.vue";
import CreateCountry from "@/views/admin/country/CreateCountry.vue";
import EditCountry from "@/views/admin/country/EditCountry.vue";
import CityList from "@/views/admin/city/CityList.vue";
import CreateCity from "@/views/admin/city/CreateCity.vue";
import EditCity from "@/views/admin/city/EditCity.vue";
import InstructorList from "@/views/admin/instructor/InstructorList.vue";
import CreateInstructor from "@/views/admin/instructor/CreateInstructor.vue";
import EditInstructor from "@/views/admin/instructor/EditInstructor.vue";

import { useFetchDataStore } from "@/stores/fetchDataStore";
import { useErrorStore } from "@/stores/errorStore";
import { useAuthStore } from "@/stores/authStore";
import CertificateList from "@/views/admin/certificate/CertificateList.vue";
import CreateCertificate from "@/views/admin/certificate/CreateCertificate.vue";
import EditCertificate from "@/views/admin/certificate/EditCertificate.vue";
import CategoryList from "@/views/admin/category/CategoryList.vue";
import CreateCategory from "@/views/admin/category/CreateCategory.vue";
import EditCategory from "@/views/admin/category/EditCategory.vue";
import Container from "@/views/admin/Container.vue";
import Dashboard from "@/views/admin/Dashboard.vue";
import EventList from "@/views/admin/event/EventList.vue";
import CreateEvent from "@/views/admin/event/CreateEvent.vue";
import EditEvent from "@/views/admin/event/EditEvent.vue";
import EventCategoryList from "@/views/admin/eventCategory/EventCategoryList.vue";
import CreateEventCategory from "@/views/admin/eventCategory/CreateEventCategory.vue";
import EditEventCategory from "@/views/admin/eventCategory/EditEventCategory.vue";
import EventTypeList from "@/views/admin/eventType/EventTypeList.vue";
import CreateEventType from "@/views/admin/eventType/CreateEventType.vue";
import EditEventType from "@/views/admin/eventType/EditEventType.vue";
import LocationList from "@/views/admin/location/LocationList.vue";
import CreateLocation from "@/views/admin/location/CreateLocation.vue";
import EditLocation from "@/views/admin/location/EditLocation.vue";

import BookingList from "@/views/booking/BookingList.vue";
import BookingInstructor from "@/views/booking/BookingInstructor.vue";
import UserProfileEdit from "@/views/public/user/UserProfileEdit.vue";
import ReservationList from "@/views/admin/reservation/ReservationList.vue";
import EditReservation from "@/views/admin/reservation/EditReservation.vue";
import CreateReservation from "@/views/admin/reservation/CreateReservation.vue";
import InstructorProfile from "@/views/instructor/InstructorProfile.vue";

const routes = [
  {
    path: "/",
    name: "HomePage",
    component: HomePage,
    meta: { public: false },
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
    path: "/instructor-profile/:id",
    name: "InstructorProfile",
    component: InstructorProfile,
    props: true,
    meta: { public: false },
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
        path: "cities",
        name: "CityList",
        component: CityList,
        meta: { public: false },
      },
      {
        path: "cities/create",
        name: "CreateCity",
        component: CreateCity,
        meta: { public: false },
      },
      {
        path: "cities/:id/edit",
        name: "EditCity",
        component: EditCity,
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
        path: "reservations",
        name: "ReservationList",
        component: ReservationList,
        meta: { public: false },
      },
      {
        path: "reservations/create",
        name: "CreateReservation",
        component: CreateReservation,
        meta: { public: false },
      },
      {
        path: "reservations/:id/edit",
        name: "EditReservation",
        component: EditReservation,
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
