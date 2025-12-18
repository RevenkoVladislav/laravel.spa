<script>
export default {
    name: "Post",

    props: [
        'posts',
    ],
}
</script>

<template>
    <div v-if="posts.length">
        <h1 class="text-center font-bold text-xl mb-5 pb-3 border-b">Published Posts</h1>
        <div class="space-y-6 max-w-4xl mx-auto mb-3">
            <div v-for="post in posts" :key="post.id"
                 class="bg-gray-100 hover:bg-gray-300 rounded-md p-1 border-t border-gray-300 border hover:border-gray-600">
                <div class="p-6">
                    <h1 class="text-2xl text-center font-bold text-gray-900 mb-4 tracking-tight leading-8">
                        {{ post.title }}</h1>
                    <div v-if="post.image_url" class="mb-6 rounded-md">
                        <img :src="post.image_url" class="w-full mx-auto border hover:border-blue-500">
                    </div>
                    <p class="text-black-600">{{ post.content }}</p>
                    <p class="mt-2 text-right text-sm text-slate-500">{{ post.date }}</p>
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
