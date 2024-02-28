import { createRouter, createWebHistory } from 'vue-router';

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

router.beforeEach((to, from, next) => {
    const authorized = !!localStorage.getItem('token');

    if (!to.meta.public && !authorized) {
        next({ name: 'LoginPage' });
    } else {
        store.commit('resetFormInstance');
        next();
    }
});

export default router;