<script>
export default {
    name: "Comment",

    props: [
        'comments'
    ],
}


</script>

<template>
    <div v-if="comments.length > 0" class="mt-6 space-y-4">

        <h3 class="text-lg font-semibold text-gray-800 mb-4">Comments ({{ comments.length }})</h3>

        <div v-for="comment in comments" :key="comment.id" class="flex space-x-3 bg-gray-50 p-4 rounded-lg shadow-sm border border-gray-200 hover:border-gray-400 hover:bg-indigo-50">
            <!-- Имитация аватарки -->
            <div class="flex-shrink-0">
                <div class="h-10 w-10 rounded-full bg-indigo-500 flex items-center justify-center text-white font-bold uppercase">
                    {{ comment.user.name.charAt(0) }}
                </div>
            </div>

            <!-- Сам коммент -->
            <div class="flex-1">
                <div class="flex items-center justify-between">
                    <h4 class="text-sm font-bold text-gray-900">{{ comment.user.name }}</h4>
                    <span class="text-xs text-gray-500">{{ comment.date }}</span>
                </div>
                <p class="text-sm text-gray-700 mt-1 leading-relaxed">
                    {{ comment.body }}
                </p>

                <!-- Кнопки для ответа и лайка -->
                <div class="mt-2 flex items-center space-x-4">
                    <button class="text-xs text-gray-400 hover:text-indigo-500 transition-colors">Answer</button>
                    <button class="text-xs text-gray-400 hover:text-red-500 transition-colors">Like</button>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
