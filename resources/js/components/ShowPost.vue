<script>
import ExpandableContent from './ExpandableContent.vue';
import Comment from './Comment.vue';
export default {
    name: "ShowPost",

    components: {
        ExpandableContent,
        Comment,
    },

    props: [
        'post',
    ],

    data() {
        return {
            is_comment: false,
            is_repost: false,
            title: '',
            content: '',
            errors: {},
            body: '',
            comments: [],
        }
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
         * Метод переводит состояние is_comment в true || false для отображение комментариев
         * Тригерим метод на получение всех постов
         */
        openComment(post) {
            this.is_comment = !this.is_comment;
            this.getComments(post);
        },

        /**
         * Защитная проверка роутинга
         * Возвращает true если мы на главной странице пользователя
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
         * Отправляем api запрос с body комментария
         *
         * Ловим ошибки чтобы их выводить
         */
        storeComment(post) {
            axios.post(`/api/posts/${post.id}/comment`, {
                body: this.body
            })
                .then(response => {
                    this.body = '';
                    post.comments_count = response.data.comments_count;
                    console.log(response);
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
                });
        },

        /**
         * Метод для получения комментариев к определенному посту
         */
        getComments(post) {
           axios.get(`/api/posts/${post.id}/comment`)
               .then(response => {
                   this.comments = response.data.data;
               });
        },
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

        <!-- Вывод контента с кнопкой ReadMore -->
        <div class="expandable-content">
            <expandable-content :content="post.content" :limit="500" />
        </div>
        <!-- Конец вывода контента с кнопкой ReadMore -->

        <!-- Конец вывода поста -->

        <!-- Вывод данных из репоста -->
        <div v-if="post.reposted_post" class="bg-gray-200 p-4 my-4 border border-gray-300 hover:border-gray-400 hover:bg-indigo-100 rounded-md">
            <h1 class="text-2xl text-center font-bold text-gray-900 mb-4 tracking-tight leading-8">{{ post.reposted_post.title }}</h1>
            <div v-if="post.reposted_post.image_url" class="mb-6 rounded-md">
                <img v-if="post.reposted_post.image_url" :src="post.reposted_post.image_url" :alt="post.reposted_post.title"
                     class="w-full mx-auto border hover:border-blue-500">
            </div>

            <!-- Вывод контента с кнопкой ReadMore -->
            <div v-if="post.reposted_post" class="border-l-4 border-indigo-200 ml-4 pl-4 expandable-content">
                <expandable-content :content="post.reposted_post.content" :limit="200" />
            </div>
            <!-- Конец вывода контента с кнопкой ReadMore -->

        </div>
        <!-- Конец вывода данных из репоста -->

        <!-- Блок с иконками - Лайк, Коммент и Репост -->
        <div class="flex justify-between items-center mt-5">
            <div class="flex items-center">
                <svg @click.prevent="toggleLike(post)" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                     stroke-width="1.5" stroke="currentColor"
                     :class="['mr-2 size-6 stroke-indigo-500 cursor-pointer hover:fill-indigo-500', post.is_liked ? 'fill-indigo-500' : 'fill-gray-100']">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/>
                </svg>
                <p class="font-bold text-xl">{{ post.likes_count }}</p>

                <svg @click.prevent="openComment(post)" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                     stroke-width="1.5" stroke="currentColor"
                     :class="['mr-2 ml-5 size-6 stroke-indigo-500 cursor-pointer hover:fill-indigo-500', this.is_comment ? 'fill-indigo-500' : 'fill-gray-100']">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a5.969 5.969 0 0 1-.474-.065 4.48 4.48 0 0 0 .978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z" />
                </svg>
                <p class="font-bold text-xl">{{ post.comments_count }}</p>


                <svg @click.prevent="openRepost" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                     stroke-width="1.5" stroke="currentColor"
                     :class="['mr-2 ml-5 size-6 stroke-indigo-500 cursor-pointer hover:fill-indigo-500', this.is_repost ? 'fill-indigo-500' : 'fill-gray-100']">
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
                    <p v-if="errors.title" class="text-red-500 text-sm mt-2">
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
                    <p v-if="errors.content" class="text-red-500 text-sm mt-2">
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
        <div v-if="is_comment" class="mt-4">
            <input v-model="body" @input="errors.body = null" type="text"
                   placeholder="Enter your comment"
                   class="block w-full rounded-md bg-gray-50 px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"/>
            <p v-if="errors.body" class="text-red-500 text-sm mt-2">
                {{ errors.body[0] }}
            </p>
            <button @click.prevent="storeComment(post)" type="button" class="mt-3 flex w-25 justify-center rounded-md bg-gradient-to-r from-indigo-400 via-indigo-500 to-indigo-600 hover:bg-gradient-to-br focus:ring-2 focus:outline-none focus:ring-indigo-600 px-3 py-1.5 text-sm/6 font-semibold text-white shadow-xs focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 cursor-pointer">
                Comment
            </button>

            <!-- Вывод всех комментариев для поста -->
            <Comment :comments="this.comments"></Comment>
            <!-- Конец вывода всех комментариев для поста -->

        </div>
        <!-- Конец блока с комментариями -->

    </div>
</template>

<style scoped>
/*необходимо для предотвращения подвисания при скролинге*/
.expandable-content {
    content-visibility: auto;
    contain-intrinsic-size: 1px 100px;
}

p {
    will-change: height;
}
</style>
