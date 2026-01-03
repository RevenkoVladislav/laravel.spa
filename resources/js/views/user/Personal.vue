<script>
import Post from '../../components/Post.vue';
import Statistics from '../../components/Statistics.vue';

export default {
    name: "Personal",

    data() {
        return {
            title: "",
            content: "",
            image: null,
            errors: {},
            posts: [],
            isFormVisible: false,
            stats: [],
        }
    },

    components: {
        Post,
        Statistics,
    },

    methods: {
        /**
         * метод для сохранения постов
         * Оборачиваем в try..catch для формирования валидационных ошибок
         * формируем id для картинки, которой может и не быть.
         * Через axios передаем данные в store
         * с помощью unshift кидаем только что созданный пост в массив постов, тем самым выводим его на странице
         * в случае успеха обнуляем поля
         */
        async store() {
            try {
                const id = this.image ? this.image.id : null;
                await axios.post('/api/posts', {
                    title: this.title,
                    content: this.content,
                    image_id: id,
                }).then(response => {
                    this.posts.unshift(response.data.data);
                    this.cancelForm();
                });
            } catch (error) {
                this.errors = error.response.data.errors;
            }
        },

        /**
         * метод для добавления картинок через скрытый инпут и refs - file
         */
        selectFile() {
            this.fileInput = this.$refs.file;
            this.fileInput.click();
        },

        /**
         * Очистка всех полей формы
         */
        resetForm() {
            this.title = '';
            this.content = '';
            this.image = null;
            this.errors = {};
        },

        /**
         * Очистить форму и скрыть
         */
        cancelForm() {
            this.resetForm();
            this.isFormVisible = false;
        },

        /**
         * Временное сохранение картинок в бд
         * добавляем image в FormData, делаем запрос через api
         * сохраняем картинку в бд со статусом false
         * либо выводим ошибки
         */
        async storeImage(event) {
            try {
                const file = event.target.files[0];
                const formData = new FormData();
                formData.append('image', file);

                await axios.post('/api/post_images', formData)
                    .then(res => {
                        this.image = res.data.data;
                    })
            } catch (error) {
                this.errors = error.response.data.errors;
            }
        },

        /**
         * Получение всех постов
         */
        getPosts() {
            axios.get('/api/posts')
                .then(response => {
                    this.posts = response.data.data;
                })
        },

        /**
         * Метод для получения статистических данных
         * На персональной странице передаем параметр id = null, чтобы получить статистику по текущему пользователю.
         */
        getStats() {
            axios.post('/api/users/stats', {user_id: null})
                .then(response => {
                    this.stats = response.data.data;
                });
        },
    },

    mounted() {
        this.getPosts();
        this.getStats();
    }
}
</script>

<template>
    <div class="mt-6 sm:mx-auto sm:w-full sm:max-w-lg space-y-6">
        <Statistics :stats="stats"></Statistics>
    </div>


    <div v-if="!isFormVisible" class="flex justify-center mt-6">
        <button @click="isFormVisible = true"
                class="bg-indigo-500 text-white px-6 py-2 rounded-md font-semibold hover:bg-indigo-700 transition">
            Write Post
        </button>
    </div>

    <div v-if="isFormVisible" class="mt-6 sm:mx-auto sm:w-full sm:max-w-lg space-y-6 border p-6 rounded-lg shadow-sm">
        <div class="flex justify-between items-center border-b pb-3">
            <h3 class="text-lg font-medium">New post</h3>
            <button @click="cancelForm" class="text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br focus:ring-2 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-md text-xs px-3 py-1 text-center leading-5">
                Cancel
            </button>
        </div>
            <div>
                <label for="title" class="block text-sm/6 font-medium text-gray-900">Title</label>
                <div class="mt-2">
                    <input v-model="title" @input="errors.title = null" id="title" type="text" required
                           placeholder="Title"
                           class="block w-full rounded-md bg-gray-50 px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"/>
                    <p v-if="errors.title" class="text-red-500 text-sm mt-2">
                        {{ errors.title[0] }}
                    </p>
                </div>
            </div>

            <div>
                <label for="content" class="block text-sm/6 font-medium text-gray-900">Content</label>
                <div class="mt-2">
                    <textarea v-model="content" id="content" rows="4" @input="errors.content = null" class="block w-full rounded-md bg-gray-50 px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                  placeholder="Write your content"></textarea>
                    <p v-if="errors.content" class="text-red-500 text-sm mt-2">
                        {{ errors.content[0] }}
                    </p>
                </div>
            </div>

            <div>
                <a href="#" @click.prevent="resetForm" v-if="title || content || image" class="text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br focus:ring-2 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-md text-xs px-3 py-1 text-center leading-5">Reset Post</a>
            </div>

            <div>
                <div class="flex items-center justify-between">
                    <label class="block text-sm/6 font-medium text-gray-900">Upload Image</label>
                    <a v-if="image" @click.prevent="image = null" href="#"
                       class="text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br focus:ring-2 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-md text-xs px-3 py-1 text-center leading-5">Cancel</a>
                </div>
                <div class="mt-2">
                    <input @change="storeImage" ref="file" type="file" id="file" @input="errors.image = null"
                           class="hidden">
                    <a href="#" @click.prevent="selectFile()"
                       class="flex w-full justify-center bg-gradient-to-r from-sky-400 via-sky-500 to-sky-600 text-white rounded-md hover:bg-gradient-to-bl px-3 py-1.5 focus:ring-2 focus:outline-none focus:ring-sky-500 text-sm/6 font-semibold shadow-xs focus-visible:outline-2">Image</a>
                    <p v-if="errors.image" class="text-red-500 text-sm mt-2">
                        {{ errors.image[0] }}
                    </p>
                    <div v-if="image" class="mt-2">
                        <img :src="image.url" alt="preview">
                    </div>
                </div>

            </div>

            <div>
                <button @click.prevent="store()" type="button" class="flex w-full justify-center rounded-md bg-gradient-to-r from-indigo-400 via-indigo-500 to-indigo-600 hover:bg-gradient-to-br focus:ring-2 focus:outline-none focus:ring-indigo-600 px-3 py-1.5 text-sm/6 font-semibold text-white shadow-xs focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 cursor-pointer">
                    Publish
                </button>
            </div>
    </div>
    <div class="mt-6 sm:mx-auto sm:w-full sm:max-w-lg space-y-6">
        <Post :posts="posts" title="My Published Posts"></Post>
    </div>
</template>

<style scoped>

</style>
