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

        /**
         * удалить post из массива posts по переданному Id
         */
        removePost(postId) {
            this.posts = this.posts.filter(post => post.id !== postId);
        }
    },
}
</script>

<template>
    <div class="mt-6 sm:mx-auto sm:w-full sm:max-w-lg space-y-6">
        <Post :posts="posts" title="Feed Posts" @liked="removePost"></Post>
    </div>
</template>

<style scoped>

</style>
