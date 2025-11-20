import { createRouter, createWebHistory } from 'vue-router';
import Dashboard from '../pages/Dashboard.vue';
import Login from '../pages/Login.vue'

const routes = [
    {
        path: '/login',
        name: 'login',
        component: Login,
    },
    {
        path: '/dashboard',
        name: 'dashboard',
        component: Dashboard,
        meta: { requiresAuth: true },
    },
    {
        path: '/',
        redirect: '/dashboard',
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    next();
});

export default router;