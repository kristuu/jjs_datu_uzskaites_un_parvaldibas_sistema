import { createRouter, createWebHistory } from 'vue-router';

import HomePage from '../views/HomePage.vue';
import UserList from '../views/user/UserList.vue';
import CreateUser from '../views/user/CreateUser.vue';
import EditUser from '../views/user/EditUser.vue';
import LoginPage from '../views/auth/LoginPage.vue';
import RegisterPage from '../views/auth/RegisterPage.vue';

const routes = [
    {
        path: '/',
        name: 'HomePage',
        component: HomePage,
        meta: { public: true }
    },
    {
        path: '/users',
        name: 'UserList',
        component: UserList,
        meta: { public: false }
    },
    {
        path: '/create-user',
        name: 'CreateUser',
        component: CreateUser,
        meta: { public: false }
    },
    {
        path: '/edit-user/:id',
        name: 'EditUser',
        component: EditUser,
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
        next();
    }
});

export default router;