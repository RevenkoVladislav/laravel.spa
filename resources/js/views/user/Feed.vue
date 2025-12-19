<script>
import Post from '../../components/Post.vue';
export default {
    name: "Show",

    components: {
        Post,
    },

    data() {
        return {
            posts: [],
        }
    },

    mounted() {
        this.getPosts();
    },

    methods: {
        /**
         * Получение постов от пользователей на которых мы подписаны
         */
        getPosts() {
            axios.get(`/api/users/following_posts`)
                .then(response => {
                    this.posts = response.data.data;
                })
        },
    },
}
</script>

<template>
    <div class="mt-6 sm:mx-auto sm:w-full sm:max-w-md space-y-6">
        <Post :posts="posts"></Post>
    </div>
</template>

<style scoped>

</style>
