import { createRouter, createWebHistory } from 'vue-router';

import Login from '../views/auth/login.vue';
import Register from '../views/auth/register.vue';

const routes = [
    {
        path: '/',
        name: 'Home',
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
        name: 'Login',
        component: Login,
        meta: { public: true }
    },
    {
        path: '/register',
        name: 'Register',
        component: Register,
        meta: { public: true }
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    const isAuthorized = !!localStorage.getItem('token');

    if (!to.meta.public && !isAuthenticated) {
        next({ name: 'Login' });
    } else {
        next();
    }
});

export default router;