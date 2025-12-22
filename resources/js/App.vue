<script setup>
//цепляем глобальное состояние авторизации из Pinia
//цепляем роутер
import {useAuthStore} from './store/auth.js';
import {useRouter} from 'vue-router';

const auth = useAuthStore();
const router = useRouter();
const logout = async () => {
    await auth.logout()
    await router.push('/login');
}
</script>

<template>
    <div class="flex justify-between p-8 w-96 mx-auto">
        <router-link v-if="!auth.user" :to="{ name: 'user.login' }" class="text-white bg-gradient-to-br from-purple-600 to-blue-500 hover:bg-gradient-to-bl focus:outline-none font-medium rounded-lg text-sm px-4 py-2.5 text-center leading-5">Login</router-link>
        <router-link v-if="!auth.user" :to="{name: 'user.register' }" class="text-white bg-gradient-to-br from-purple-600 to-blue-500 hover:bg-gradient-to-bl focus:outline-none font-medium rounded-lg text-sm px-4 py-2.5 text-center leading-5">Register</router-link>
        <router-link v-if="auth.user" :to="{ name: 'user.personal' }" class="text-white bg-gradient-to-r from-cyan-500 to-blue-500 hover:bg-gradient-to-bl focus:outline-none font-medium rounded-lg text-sm px-4 py-2.5 text-center leading-5">Personal</router-link>
        <router-link v-if="auth.user" :to="{ name: 'user.index' }" class="text-white bg-gradient-to-r from-cyan-500 to-blue-500 hover:bg-gradient-to-bl focus:outline-none font-medium rounded-lg text-sm px-4 py-2.5 text-center leading-5">Users</router-link>
        <router-link v-if="auth.user" :to="{ name: 'user.feed' }" class="text-white bg-gradient-to-r from-cyan-500 to-blue-500 hover:bg-gradient-to-bl focus:outline-none font-medium rounded-lg text-sm px-4 py-2.5 text-center leading-5">Feed</router-link>
        <router-link v-if="auth.user" :to="{ name: 'user.liked-posts' }" class="text-white bg-gradient-to-r from-cyan-500 to-blue-500 hover:bg-gradient-to-bl focus:outline-none font-medium rounded-lg text-sm px-4 py-2.5 text-center leading-5">Liked Posts</router-link>
        <button v-if="auth.user" @click="logout" class="text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br focus:outline-none font-medium rounded-lg text-sm px-4 py-2.5 text-center leading-5 cursor-pointer">Logout</button>
    </div>
    <router-view/>
</template>

<style scoped>

</style>
