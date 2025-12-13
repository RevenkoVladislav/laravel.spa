<script setup>
import {reactive} from 'vue'
import {useAuthStore} from '../../store/auth.js';
import {useRouter} from 'vue-router';

const auth = useAuthStore();
const router = useRouter();
const form = reactive({email: '', password: ''});
const submit = async () => {
    await auth.login(form);
    await router.push('/personal');
}
</script>

<template>
    <form @submit.prevent="submit">
        <input v-model="form.email" placeholder="email"/>
        <input v-model="form.password" type="password"/>
        <button>Login</button>
    </form>
</template>

<style scoped>

</style>
