<script>
import ShowPost from './ShowPost.vue';
export default {
    name: "Post",

    components: {
        ShowPost,
    },

    props: [
        'posts',
        'title',
    ],
}
</script>

<template>
<!--  Проверка на наличие постов, стилизация блоков, и цикл по постам  -->
    <div v-if="posts.length">
        <h1 class="text-center font-bold text-xl mb-5 pb-3 border-b">{{ title }}</h1>
        <div class="space-y-6 max-w-4xl mx-auto mb-3">
            <div v-for="post in posts" :key="post.id"
                 class="bg-gray-100 hover:bg-gray-300 rounded-md p-1 border-t border-gray-300 border hover:border-gray-600">

                <!-- Передача управления по выводу постов в ShowPost компонент + проброс событий для лайков -->
                <ShowPost :post="post"
                          @liked="$emit('liked', $event)"
                          @unliked="$emit('unliked', $event)"
                ></ShowPost>
                <!-- Конец вывода поста -->

            </div>
        </div>
    </div>
    <div v-else class="text-center text-gray-400">
        No posts yet
    </div>
<!-- конец секции с постами -->
</template>

<style scoped>

</style>
