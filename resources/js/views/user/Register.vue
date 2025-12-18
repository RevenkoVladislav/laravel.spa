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
        await router.push({name: 'user.personal'});
    }
}
</script>

<template>
        <div class="sm:mx-auto sm:w-full sm:max-w-sm">
            <h2 class="mt-10 text-center text-2xl/9 font-bold tracking-tight text-gray-900">Create account</h2>
        </div>

        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
            <form @submit.prevent="submit" class="space-y-6">

                <div>
                    <label for="name" class="block text-sm/6 font-medium text-gray-900">Name</label>
                    <div class="mt-2">
                        <input v-model="form.name" id="name" type="text" name="name" required placeholder="Name" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
                    </div>
                    <p v-if="errors.name" class="text-red-500">
                        {{ errors.name[0] }}
                    </p>
                </div>

                <div>
                    <label for="email" class="block text-sm/6 font-medium text-gray-900">Email</label>
                    <div class="mt-2">
                        <input v-model="form.email" id="email" type="email" name="email" required placeholder="Email" autocomplete="email" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
                    </div>
                    <p v-if="errors.email" class="text-red-500">
                        {{ errors.email[0] }}
                    </p>
                </div>

                <div>
                    <label for="password" class="block text-sm/6 font-medium text-gray-900">Password</label>
                    <div class="mt-2">
                        <input v-model="form.password" id="password" type="password" name="password" placeholder="Password" required autocomplete="password" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
                    </div>
                    <p v-if="errors.password" class="text-red-500">
                        {{ errors.password[0] }}
                    </p>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm/6 font-medium text-gray-900">Password confirmed</label>
                    <div class="mt-2">
                        <input v-model="form.password_confirmation" id="password_confirmation" type="password" placeholder="Password confirmation" name="password_confirmation" required autocomplete="password_confirmation" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
                    </div>
                    <p v-if="errors.password" class="text-red-500">
                        {{ errors.password[0] }}
                    </p>
                </div>

                <div>
                    <button type="submit" class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm/6 font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 cursor-pointer">Registration</button>
                </div>
            </form>

        </div>
</template>

<style scoped>

</style>
