<script setup>
import {reactive, computed} from 'vue'
import {useAuthStore} from '../../store/auth.js';
import {useRouter} from 'vue-router';

const auth = useAuthStore();
const router = useRouter();
const form = reactive({
    email: '',
    password: ''
});

//реактивная зависимость, которая ловит ошибки и динамически их возвращает
const errors = computed(() => auth.errors);

const submit = async () => {
    const success = await auth.login(form);
    if (success) {
        await router.push('/personal');
    }
}
</script>

<template>
    <form @submit.prevent="submit">
        <div>
            <input v-model="form.email" placeholder="email"/>
            <p v-if="errors.message" class="text-red-500">
                {{ errors.message[0] }}
            </p>
        </div>

        <div>
            <input v-model="form.password" type="password" placeholder="password"/>
            <p v-if="errors.message" class="text-red-500">
                {{ errors.message[0] }}
            </p>
        </div>

        <button>Login</button>
    </form>
</template>

<style scoped>

</style>
