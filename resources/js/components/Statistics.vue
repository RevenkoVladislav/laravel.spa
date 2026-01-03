<script>
export default {
    name: "Statistics",

    props: [
        'stats'
    ],
}
</script>

<template>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-10">
        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow text-center">
            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Subscribers</dt>
            <dd class="text-2xl font-bold text-indigo-600">{{ stats.subscribers_count }}</dd>
        </div>

        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow text-center">
            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Likes</dt>
            <dd class="text-2xl font-bold text-indigo-600">{{ stats.likes_count }}</dd>
        </div>

        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow text-center">
            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Posts</dt>
            <dd class="text-2xl font-bold text-indigo-600">{{ stats.posts_count }}</dd>
        </div>

        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow text-center">
            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Followings</dt>
            <dd class="text-2xl font-bold text-indigo-600">{{ stats.followings_count }}</dd>
        </div>
    </div>
</template>

<style scoped>

</style>
