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
            <div v-for="user in users">
                <p>{{ user.id}}</p>
                <p>{{ user.name}}</p>
                <p>{{ user.email}}</p>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
