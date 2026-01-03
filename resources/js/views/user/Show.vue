<script>
import Post from '../../components/Post.vue';
import Statistics from "../../components/Statistics.vue";
export default {
    name: "Show",

    components: {
        Post,
        Statistics,
    },

    data() {
        return {
            posts: [],
            userId: this.$route.params.id,
            stats: {
                subscribers_count: 0,
                followings_count: 0,
                likes_count: 0,
                posts_count: 0,
            },
        }
    },

    mounted() {
        this.getPosts();
        this.getStats();
    },

    methods: {
        /**
         * Получение всех постов
         */
        getPosts() {
            axios.get(`/api/users/${this.userId}/posts`)
                .then(response => {
                    this.posts = response.data.data;
                })
        },

        /**
         * Метод для получения статистических данных
         * На персональной странице передаем параметр id = null, чтобы получить статистику по текущему пользователю.
         */
        getStats() {
            axios.get('/api/users/stats', {user_id: this.userId})
                .then(response => {
                    this.stats = response.data.data;
                });
        },
    },
}
</script>

<template>
    <div class="mt-6 sm:mx-auto sm:w-full sm:max-w-lg space-y-6">
        <Statistics :stats="stats"></Statistics>
        <Post :posts="posts" title="Published Posts"></Post>
    </div>
</template>

<style scoped>

</style>
