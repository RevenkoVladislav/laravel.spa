<script>
export default {
    name: "ShowPost",

    props: [
        'post',
    ],

    data() {
        return {
            is_repost: false,
            title: '',
            content: '',
            errors: {},
            body: '',
            isExpanded: false,
            limit: 500,
        }
    },

    computed: {
        /**
         * Метод для лимита символов в контенте поста
         * Проверка на пустой content для исключения ошибок
         * Если текст короче лимита то ничего не трогаем
         * При достижении лимита в конце строки поставить троеточие
         */
        truncatedContent() {
            if (!this.post?.content) return '';

            if (this.post.content.length <= this.limit) {
                return this.post.content;
            }

            return this.post.content.slice(0, this.limit) + '...';
        },
    },

    methods: {

        /**
         * Отправляем api запрос,
         * Привязка/отвязка лайка
         * Запись кол-ва лайков
         *
         * Если посту поставили лайк, запустить событие liked у родителя
         * Если сняли лайк запустить событие unliked у родителя
         */
        toggleLike(post) {
            axios.post(`/api/posts/${post.id}/toggle_like`)
                .then(response => {
                    post.is_liked = response.data.is_liked;
                    post.likes_count = response.data.likes_count;

                    if (response.data.is_liked) {
                        this.$emit('liked', post.id)
                    } else {
                        this.$emit('unliked', post.id);
                    }
                });
        },

        /**
         * Метод переводит состояние is_repost в true || false для отображения формы репоста
         * Если мы на домашней странице пользователя то репост невозможен
         */
        openRepost() {
            if (this.isPersonal()) {
                return;
            }
            this.is_repost = !this.is_repost;
        },

        /**
         * Если нет ошибок и is_repost активный
         * То переведи его в false
         */
        closedRepost() {
            if (Object.keys(this.errors).length === 0 && this.is_repost) {
                this.is_repost = false;
            }
        },

        /**
         * Защитная проверка роутинга
         * Возвращает true если мы на главное странице пользователя
         * @returns {boolean}
         */
        isPersonal() {
          return this.$route.name === 'user.personal';
        },

        /**
         * Если мы на главное странице то respot не будет выполнен
         * Обнуляем ошибки
         * Передаем через axios данные по репосту на бэк
         * После отправки обнуляем данные из формы
         * И закрываем форму
         * Ловим валидационные ошибки
         */
        repost(post) {
            if (this.isPersonal()) {
                return;
            }
            this.errors = {};
            axios.post(`/api/posts/${post.id}/repost`, {
                title: this.title,
                content: this.content,
            }).then(response => {
                this.title = '';
                this.content = '';
                post.reposted_count = response.data.reposted_count;
                this.closedRepost();
            }).catch(error => {
                this.errors = error.response.data.errors;
            });
        },

        /**
         * Метод по созданию коммента.
         */
        storeComment() {
            axios.post(`/api/posts/${post.id}/comment`, {

            })
        }
    },
}
</script>

<template>
    <!-- Вывод поста - заголовок, картинка, контент -->
    <div class="p-6">
        <h1 class="text-2xl text-center font-bold text-gray-900 mb-4 tracking-tight leading-8">{{ post.title }}</h1>
        <div v-if="post.image_url" class="mb-6 rounded-md">
            <img v-if="post.image_url" :src="post.image_url" :alt="post.title"
                 class="w-full mx-auto border hover:border-blue-500">
        </div>
        <p class="text-black-600">
            {{ isExpanded ? post.content : truncatedContent }}
        </p>
        <button v-if="post.content.length > 500" @click="isExpanded = !isExpanded"
            class="text-indigo-500 hover:underline">
            {{ isExpanded ? 'Hide' : 'Read more' }}
        </button>
        <!-- Конец вывода поста -->

        <!-- Вывод данных из репоста -->
        <div v-if="post.reposted_post" class="bg-gray-200 p-4 my-4 border border-gray-300 hover:border-gray-400 hover:bg-indigo-100 rounded-md">
            <h1 class="text-2xl text-center font-bold text-gray-900 mb-4 tracking-tight leading-8">{{ post.reposted_post.title }}</h1>
            <div v-if="post.reposted_post.image_url" class="mb-6 rounded-md">
                <img v-if="post.reposted_post.image_url" :src="post.reposted_post.image_url" :alt="post.reposted_post.title"
                     class="w-full mx-auto border hover:border-blue-500">
            </div>
            <p class="text-black-600">{{ post.reposted_post.content }}</p>
        </div>
        <!-- Конец вывода данных из репоста -->

        <!-- Блок с иконками - Лайк и Репост -->
        <div class="flex justify-between items-center mt-5">
            <div class="flex items-center">
                <svg @click.prevent="toggleLike(post)" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                     stroke-width="1.5" stroke="currentColor"
                     :class="['mr-2 size-6 stroke-indigo-500 cursor-pointer hover:fill-indigo-500', post.is_liked ? 'fill-indigo-500' : 'fill-gray-100']">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/>
                </svg>
                <p class="font-bold text-xl">{{ post.likes_count }}</p>

                <svg @click.prevent="openRepost" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                     stroke-width="1.5" stroke="currentColor"
                     :class="['mr-2 ml-5 size-6 stroke-indigo-500 cursor-pointer hover:fill-indigo-500', post.is_repost ? 'fill-indigo-500' : 'fill-gray-100']">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M7.217 10.907a2.25 2.25 0 1 0 0 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186 9.566-5.314m-9.566 7.5 9.566 5.314m0 0a2.25 2.25 0 1 0 3.935 2.186 2.25 2.25 0 0 0-3.935-2.186Zm0-12.814a2.25 2.25 0 1 0 3.933-2.185 2.25 2.25 0 0 0-3.933 2.185Z"/>
                </svg>
                <p class="font-bold text-xl">{{ post.reposted_count }}</p>
            </div>
            <p class="text-right text-sm text-slate-500">{{ post.date }}</p>
        </div>
        <!-- Конец блока с иконками -->

        <!-- Блок для репоста -->
        <div v-if="is_repost" class="mt-3">
            <div class="flex justify-center items-center border-b pb-3">
                <p class="text-lg font-medium">Write repost</p>
            </div>

            <div>
                <label for="title" class="mt-2 block text-sm/6 font-medium text-gray-900">Title</label>
                <div class="mt-2">
                    <input v-model="title" @input="errors.title = null" id="title" type="text" required
                           placeholder="Title"
                           class="block w-full rounded-md bg-gray-50 px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"/>
                    <p v-if="errors.title" class="text-red-500">
                        {{ errors.title[0] }}
                    </p>
                </div>
            </div>

            <div>
                <label for="content" class="mt-2 block text-sm/6 font-medium text-gray-900">Content</label>
                <div class="mt-2">
                    <textarea v-model="content" id="content" rows="4" @input="errors.content = null"
                              class="block w-full rounded-md bg-gray-50 px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                              placeholder="Write your content"></textarea>
                    <p v-if="errors.content" class="text-red-500">
                        {{ errors.content[0] }}
                    </p>
                </div>
            </div>

            <div>
                <button @click.prevent="repost(post)" type="button" class="mt-3 flex w-25 justify-center rounded-md bg-gradient-to-r from-indigo-400 via-indigo-500 to-indigo-600 hover:bg-gradient-to-br focus:ring-2 focus:outline-none focus:ring-indigo-600 px-3 py-1.5 text-sm/6 font-semibold text-white shadow-xs focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 cursor-pointer">
                    Repost
                </button>
            </div>
        </div>
        <!-- Конец блока репоста -->

        <!-- Блок с комментариями -->
        <div class="mt-2">
            <input v-model="body" @input="errors.body = null" type="text"
                   placeholder="Enter your comment"
                   class="block w-full rounded-md bg-gray-50 px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"/>
            <p v-if="errors.body" class="text-red-500">
                {{ errors.body[0] }}
            </p>
            <button @click.prevent="storeComment" type="button" class="mt-3 flex w-25 justify-center rounded-md bg-gradient-to-r from-indigo-400 via-indigo-500 to-indigo-600 hover:bg-gradient-to-br focus:ring-2 focus:outline-none focus:ring-indigo-600 px-3 py-1.5 text-sm/6 font-semibold text-white shadow-xs focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 cursor-pointer">
                Comment
            </button>
        </div>
        <!-- Конец блока с комментариями -->

    </div>
</template>

<style scoped>

</style>
