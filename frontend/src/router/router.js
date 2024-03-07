import { createRouter, createWebHistory } from 'vue-router';
import axios from '@/services/axios.js';

import HomePage from '../views/HomePage.vue';
import UserList from '@/views/user/UserList.vue';
import CreateUser from '../views/user/CreateUser.vue';
import EditUser from '../views/user/EditUser.vue';
import LoginPage from '../views/auth/LoginPage.vue';
import RegisterPage from '../views/auth/RegisterPage.vue';
import InstructorsList from "@/views/booking/InstructorsList.vue";

import AdminDashboard from "@/views/admin/AdminDashboard.vue";

import MaugloCompute from '@/views/calculateReportDiapazone.vue'
import store from "@/auth/store";
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

const routes = [
    {
        path: '/',
        name: 'HomePage',
        component: HomePage,
        meta: { public: true }
    },
    {
        path: '/booking',
        name: 'InstructorsList',
        component: InstructorsList,
        meta: { public: false }
    },
    {
        path: '/login',
        name: 'LoginPage',
        component: LoginPage,
        meta: { public: true }
    },
    {
        path: '/register',
        name: 'RegisterPage',
        component: RegisterPage,
        meta: { public: true }
    },
    {
        path: '/mauglo',
        name: 'MaugloCompute',
        component: MaugloCompute,
        meta: { public: false }
    },
    {
        path: '/error',
        name: 'ErrorView',
        component: ErrorView,
        meta: { public: true }
    },
    {
      path: '/admin',
      component: AdminDashboard,
      children: [
          {
              path: '', name: 'AdminDashboard', component: AdminDashboard, meta: { public: false },
          },

          {
              path: 'users', name: 'UserList', component: UserList, meta: { public: false },
          },
          {
              path: 'users/:id/edit',  name: 'EditUser', component: EditUser, meta: { public: false },
          },
          {
              path: 'users/create',  name: 'CreateUser', component: CreateUser, meta: { public: false },
          },

          {
            path: 'permissions', name: 'PermissionList', component: PermissionList, meta: { public: false },
          },
          {
            path: 'permissions/create', name: 'CreatePermission', component: CreatePermission, meta: { public: false },
          },
          {
            path: 'permissions/:id/edit', name: 'EditPermission', component: EditPermission, meta: { public: false },
          },

          {
              path: 'roles', name: 'RoleList', component: RoleList, meta: { public: false },
          },
          {
              path: 'roles/create', name: 'CreateRole', component: CreateRole, meta: { public: false },
          },
          {
              path: 'roles/:id/edit', name: 'EditRole', component: EditRole, meta: { public: false },
          },

          {
              path: 'countries', name: 'CountryList', component: CountryList, meta: { public: false },
          },
          {
              path: 'countries/create', name: 'CreateCountry', component: CreateCountry, meta: { public: false },
          },
          {
              path: 'countries/:id/edit', name: 'EditCountry', component: EditCountry, meta: { public: false },
          },

          {
              path: 'regions', name: 'RegionList', component: RegionList, meta: { public: false },
          },
          {
              path: 'regions/create', name: 'CreateRegion', component: CreateRegion, meta: { public: false },
          },
          {
              path: 'regions/:id/edit', name: 'EditRegion', component: EditRegion, meta: { public: false },
          },

          {
              path: 'instructors', name: 'InstructorList', component: InstructorList, meta: { public: false },
          },
          {
              path: 'instructors/create', name: 'CreateInstructor', component: CreateInstructor, meta: { public: false },
          },
          {
              path: 'instructors/:id/edit', name: 'EditInstructor', component: EditInstructor, meta: { public: false },
          },
      ],
    },
    {
        path: '/:pathMatch(.*)*', component: ErrorView,
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

async function isUserAuthorized() {
    try {
        const response = await axios.get('/user');
        return response.status === 200;
    } catch (error) {
        return false;
    }
}

router.beforeEach((to, from, next) => {
    const authorized = isUserAuthorized();

    if (!to.meta.public && !authorized) {
        next({ name: 'LoginPage' });
    } else {
        axios.get('/get-permissions').then(response => {
            window.Laravel.jsPermissions = response.data;
            next();
        });
    }
});

export default router;