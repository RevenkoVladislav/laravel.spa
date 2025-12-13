import {createRouter, createWebHistory} from 'vue-router'
import {useAuthStore} from '../store/auth.js';

//Прописываем маршруты. Закрываем meta для навигации
const routes = [
    {
        path: '/login',
        name: 'login',
        component: () => import('../view/user/Login.vue'),
        meta: {guest: true}
    },
    {
        path: '/register',
        name: 'register',
        component: () => import('../view/user/Register.vue'),
        meta: {guest: true}
    },
    {
        path: '/personal',
        name: 'personal',
        component: () => import('../view/user/Personal.vue'),
        meta: {auth: true}
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
