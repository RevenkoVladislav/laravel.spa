<script>
import Post from '../../components/Post.vue';
export default {
    name: "LikedPosts",

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
        getPosts() {
            axios.get('/api/users/liked_posts')
                .then(response => {
                    this.posts = response.data.data;
                });
        },
    },
}
</script>

<template>
    <div class="mt-6 sm:mx-auto sm:w-full sm:max-w-md space-y-6">
        <Post :posts="posts" title="Liked Posts"></Post>
    </div>
</template>

<style scoped>

</style>
