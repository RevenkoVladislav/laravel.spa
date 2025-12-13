<script setup>
import { reactive, computed } from 'vue'
import { useAuthStore } from '../../store/auth.js';
import { useRouter } from 'vue-router';


const auth = useAuthStore();
const router = useRouter();

const form = reactive({
    name:'',
    email:'',
    password:'',
    password_confirmation:''
});

//реактивная зависимость, которая ловит ошибки и динамически их возвращает
const errors = computed(() => auth.errors);

const submit = async () => {
    const success = await auth.register(form);
    if (success) {
        await router.push('/personal');
    }
}
</script>

<template>
    <form @submit.prevent="submit">
        <div>
            <input v-model="form.name" type="text" placeholder="Name" />
            <p v-if="errors.name" class="text-red-500">
                {{ errors.name[0] }}
            </p>
        </div>

        <div>
            <input v-model="form.email" type="email" placeholder="email" />
            <p v-if="errors.email" class="text-red-500">
                {{ errors.email[0] }}
            </p>
        </div>

        <div>
            <input v-model="form.password" type="password" placeholder="password"/>
            <p v-if="errors.password" class="text-red-500">
                {{ errors.password[0] }}
            </p>
        </div>

        <div>
            <input v-model="form.password_confirmation" type="password" placeholder="password_confirmation"/>
            <p v-if="errors.password" class="text-red-500">
                {{ errors.password[0] }}
            </p>
        </div>

        <button>Register</button>
    </form>
</template>

<style scoped>

</style>
