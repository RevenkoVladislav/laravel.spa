import {createRouter, createWebHistory} from 'vue-router'
import {useAuthStore} from '../store/auth.js';

//Прописываем маршруты. Закрываем meta для навигации
const routes = [
    {
        path: '/login',
        name: 'user.login',
        component: () => import('../views/user/Login.vue'),
        meta: {guest: true}
    },
    {
        path: '/register',
        name: 'user.register',
        component: () => import('../views/user/Register.vue'),
        meta: {guest: true}
    },
    {
        path: '/users/personal',
        name: 'user.personal',
        component: () => import('../views/user/Personal.vue'),
        meta: {auth: true}
    },
    {
        path: '/users/index',
        name: 'user.index',
        component: () => import('../views/user/Index.vue'),
        meta: {auth: true}
    },
    {
        path: '/users/feed',
        name: 'user.feed',
        component: () => import('../views/user/Feed.vue'),
        meta: {auth: true}
    },
    {
        path: '/users/liked-posts',
        name: 'user.liked-posts',
        component: () => import('../views/user/LikedPosts.vue'),
        meta: {auth: true}
    },
    {
        path: '/users/:id',
        name: 'user.show',
        component: () => import('../views/user/Show.vue'),
        meta: {auth:true},
    },
]

//создаем роутер
const router = createRouter({
    history: createWebHistory(),
    routes,
})


router.beforeEach(async (to) => {

    //подключаем глобальное состояние авторизации из Pinia
    const auth = useAuthStore();

    //проверяем пользователя
    if (!auth.checked) {
        await auth.fetchUser();
    }

    //маршрут требует аавторизацию и пользователь не залогинен - редирект на login
    if (to.meta.auth && !auth.user) {
        return '/login';
    }

    //пользователь авторизован - редирект на personal
    if (to.meta.guest && auth.user) {
        return '/personal';
    }
})

export default router
