<script setup>
import { useAuthStore } from './store/auth.js';
import { useRouter } from 'vue-router';

const auth = useAuthStore();
const router = useRouter();
const logout = async () => {
    await auth.logout()
    await router.push('/login');
}
</script>

<template>
    <nav class="p-4 flex gap-4">
        <router-link v-if="!auth.user" to="/login">Login</router-link>
        <router-link v-if="!auth.user" to="/register">Register</router-link>
        <router-link v-if="auth.user" to="/personal">Personal</router-link>
        <button v-if="auth.user" @click="logout">Logout</button>
    </nav>
    <router-view />
</template>

<style scoped>

</style>
