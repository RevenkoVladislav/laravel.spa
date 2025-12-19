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
        getUsers() {
            axios.get('api/users/')
                .then(response => {
                    this.users = response.data.data;
                })
        },

        toggleFollowing(user) {
            axios.get(`/api/users/${user.id}/toggle_following`)
                .then(response => {
                    user.is_following = response.data.is_following;
                })
        },
    },
}
</script>

<template>
    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-md space-y-6">
        <div v-if="users.length">
            <div class="space-y-6 max-w-4xl mx-auto mb-3">
                <div v-for="user in users" :key="user.id" class="bg-gray-100 hover:bg-gray-300 rounded-md p-1 border-t border-gray-300 border hover:border-gray-600">
                    <div class="p-6">
                        <h1 class="text-2xl text-center font-bold text-gray-900 mb-4 tracking-tight leading-8 hover:text-green-600"><router-link :to="{name: 'user.show', params: {id: user.id}}">{{ user.name }}</router-link></h1>
                        <div class="flex justify-between items-center">
                            <p class="mt-2 text-left text-sm text-slate-500">id: {{ user.id }}</p>
                            <p class="text-black-600 hover:text-green-600"><router-link :to="{name: 'user.show', params: {id: user.id}}">{{ user.email }}</router-link></p>
                            <a v-if="!user.is_following" @click.prevent="toggleFollowing(user)" href="#" class="bg-gradient-to-r from-green-400 via-green-500 to-green-600 hover:bg-gradient-to-br focus:ring-2 focus:outline-none focus:ring-green-600 dark:focus:ring-green-800 font-medium rounded-md text-xs px-3 py-1 text-center leading-5">follow</a>
                            <a v-if="user.is_following" @click.prevent="toggleFollowing(user)" href="#" class="bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br focus:ring-2 focus:outline-none focus:ring-red-600 dark:focus:ring-red-800 font-medium rounded-md text-xs px-3 py-1 text-center leading-5">unfollow</a>
                        </div>
                        <div class="flex mt-5 justify-between items-center">
                            <p>Likes: 1</p>
                            <p>Posts: 1</p>
                            <p>Followers: 1</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
