<script>
export default {
    name: "Post",

    props: [
        'posts',
        'title',
    ],

    /**
     * Отправляем api запрос,
     * Привязка/отвязка лайка
     * Запись кол-ва лайков
     */
    methods: {
        toggleLike(post) {
            axios.post(`/api/posts/${post.id}/toggle_like`)
                .then(response => {
                    post.is_liked = response.data.is_liked;
                    post.likes_count = response.data.likes_count;
                })
        },
    },
}
</script>

<template>
    <div v-if="posts.length">
        <h1 class="text-center font-bold text-xl mb-5 pb-3 border-b">{{ title }}</h1>
        <div class="space-y-6 max-w-4xl mx-auto mb-3">
            <div v-for="post in posts" :key="post.id"
                 class="bg-gray-100 hover:bg-gray-300 rounded-md p-1 border-t border-gray-300 border hover:border-gray-600">
                <div class="p-6">
                    <h1 class="text-2xl text-center font-bold text-gray-900 mb-4 tracking-tight leading-8">
                        {{ post.title }}</h1>
                    <div v-if="post.image_url" class="mb-6 rounded-md">
                        <img v-if="post.image_url" :src="post.image_url" :alt="post.title" class="w-full mx-auto border hover:border-blue-500">
                    </div>
                    <p class="text-black-600">{{ post.content }}</p>
                    <div class="flex justify-between items-center mt-5">
                        <div class="flex items-center">
                            <svg @click.prevent="toggleLike(post)" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" :class="['mr-2 size-6 stroke-indigo-500 cursor-pointer hover:fill-indigo-500', post.is_liked ? 'fill-indigo-500' : 'fill-gray-100']">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                            </svg>
                            <p class="font-bold text-xl">{{ post.likes_count }}</p>
                        </div>
                        <p class="text-right text-sm text-slate-500">{{ post.date }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div v-else class="text-center text-gray-400">
        No posts yet
    </div>
</template>

<style scoped>

</style>
