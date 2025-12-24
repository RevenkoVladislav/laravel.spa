<script>
export default {
    name: "ExpandableContent",

    props: {
        content: {
            type: String,
            default: '',
        },
        limit: {
            type: Number,
            default: 500,
        },
    },

    data() {
        return {
            isExpanded: false,
        }
    },

    computed: {
        /**
         * кнопку показываем если есть контент и он превышает лимит
         */
        shouldShowButton() {
            return this.content && this.content.length > this.limit;
        },

        /**
         * Метод для лимита символов в контенте поста
         * Проверка на пустой content для исключения ошибок
         * Если текст короче лимита то ничего не трогаем
         * При достижении лимита в конце строки поставить троеточие
         */
        truncatedContent() {
            if (!this.content) return '';
            if (!this.shouldShowButton) return this.content;

            return this.content.slice(0, this.limit) + '...';
        }
    },
}
</script>

<template>
<div>
    <p class="text-black-600 whitespace-pre-line">
        {{ isExpanded ? content : truncatedContent }}
    </p>
    <button
        v-if="shouldShowButton"
        @click="isExpanded = !isExpanded"
        class="text-indigo-500 hover:underline mt-1">
        {{ isExpanded ? 'Hide' : 'Read more' }}
    </button>
</div>
</template>

<style scoped>

</style>
