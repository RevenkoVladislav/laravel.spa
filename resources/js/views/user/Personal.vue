<script>
export default {
  name: "Personal",

  data() {
    return {

    }
  },
}
</script>

<template>
  <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm space-y-6">
    <div>
      <label for="title" class="block text-sm/6 font-medium text-gray-900">Title</label>
      <div class="mt-2">
        <input v-model="title" id="title" type="text" required placeholder="Title"
               class="block w-full rounded-md bg-gray-50 px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"/>
      </div>
    </div>

    <div>
      <label for="content" class="block text-sm/6 font-medium text-gray-900">Content</label>
      <div class="mt-2">
        <textarea v-model="content" id="content" rows="4"
                  class="block w-full rounded-md bg-gray-50 px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                  placeholder="Write your content"></textarea>
      </div>
    </div>

    <div>
      <button type="button"
              class="flex w-full justify-center rounded-md bg-gradient-to-r from-indigo-400 via-indigo-500 to-indigo-600 hover:bg-gradient-to-br focus:outline-none px-3 py-1.5 text-sm/6 font-semibold text-white shadow-xs focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
        Publish
      </button>
    </div>
  </div>
</template>

<style scoped>

</style>
