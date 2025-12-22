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
    <div v-if="!auth.user" class="flex justify-between p-8 w-96 mx-auto">
        <router-link :to="{ name: 'user.login' }" class="text-white bg-gradient-to-br from-purple-600 to-blue-500 hover:bg-gradient-to-bl focus:outline-none font-medium rounded-lg text-sm px-4 py-2.5 text-center leading-5">Login</router-link>
        <router-link :to="{name: 'user.register' }" class="text-white bg-gradient-to-br from-purple-600 to-blue-500 hover:bg-gradient-to-bl focus:outline-none font-medium rounded-lg text-sm px-4 py-2.5 text-center leading-5">Register</router-link>
    </div>
    <div v-if="auth.user" class="flex justify-between mt-6 mb-10 sm:mx-auto sm:w-full sm:max-w-md">
        <router-link :to="{ name: 'user.personal' }" class="text-white bg-gradient-to-r from-cyan-500 to-blue-500 hover:bg-gradient-to-bl focus:outline-none font-medium rounded-lg text-sm px-4 py-2.5 text-center leading-5">Personal</router-link>
        <router-link :to="{ name: 'user.index' }" class="text-white bg-gradient-to-br from-purple-600 to-blue-500 hover:bg-gradient-to-bl focus:outline-none font-medium rounded-lg text-sm px-4 py-2.5 text-center leading-5">Users</router-link>
        <router-link :to="{ name: 'user.feed' }" class="text-white bg-gradient-to-r from-green-500 to-green-600 hover:bg-gradient-to-bl focus:outline-none font-medium rounded-lg text-sm px-4 py-2.5 text-center leading-5">Feed</router-link>
        <router-link :to="{ name: 'user.liked-posts' }" class="text-white bg-gradient-to-br from-purple-600 to-blue-500 hover:bg-gradient-to-bl focus:outline-none font-medium rounded-lg text-sm px-4 py-2.5 text-center leading-5">Liked Posts</router-link>
        <button @click="logout" class="text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br focus:outline-none font-medium rounded-lg text-sm px-4 py-2.5 text-center leading-5 cursor-pointer">Logout</button>
    </div>
    <router-view/>
</template>

<style scoped>

</style>
