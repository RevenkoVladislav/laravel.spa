<script>
export default {
    name: "Index",

    data() {
        return {
            users: [],
        }
    },

    mounted() {
        this.getUsers();
    },

    methods: {
        async getUsers() {
            await axios.get('api/users/')
                .then(response => {
                    this.users = response.data.data;
                })
        },
    },
}
</script>

<template>
    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-md space-y-6">
        <div v-if="users">
            <div class="space-y-6 max-w-4xl mx-auto mb-3">
                <div v-for="user in users"
                     class="bg-gray-100 hover:bg-gray-300 rounded-md p-1 border-t border-gray-300 border hover:border-gray-600">
                    <div class="p-6">
                        <h1 class="text-2xl text-center font-bold text-gray-900 mb-4 tracking-tight leading-8">
                            {{ user.name }}</h1>
                        <p class="mt-2 text-right text-sm text-slate-500">id: {{ user.id }}</p>
                        <p class="text-black-600 text-center">{{ user.email }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
