import {defineStore} from 'pinia'
import axios from 'axios'

axios.defaults.withCredentials = true
axios.defaults.baseURL = '/'

//Создаю глобальный store с именем auth
export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: null,
        checked: false,
        //ошибки валидации
        errors: {}
    }),
    actions: {
        //checked = true нужно чтобы пришел ответ в beforeEach, иначе бесконечная загрузка
        async fetchUser() {
            try {
                const {data} = await axios.get('/api/user');
                this.user = data;
            } catch {
                this.user = null;
            } finally {
                this.checked = true;
            }
        },

        /*
         обнуляем ошибки
         получаем csrf cookie, пробуем авторизоваться
         В блоке try...catch обязательное возвращаем true или false
         Необходимо для проверок и вывод валидационных ошибок в UI пользователю
         если ошибка валидации то ловим данные и выводим сообщение. Иначе выкидываем ошибку.
         */
        async login(form) {
            this.errors = {}

            try {
                await axios.get('/sanctum/csrf-cookie');
                const {data} = await axios.post('/login', form);
                this.user = data;
                return true;
            } catch (error) {
                if (error.response?.status === 422 || error.response?.status === 401) {
                    this.errors = {
                        message: ['Wrong username or password'],
                    }
                    return false;
                }
                throw error;
            }
        },

        /*
        получаем куки, отправляем запрос на регистрацию
        обнуляем ошибки
        В блоке try...catch обязательное возвращаем true или false
        Необходимо для проверок и вывод валидационных ошибок в UI пользователю
        если ошибки связаны с валидацией либо пользователь ввел неверные данные, то получаем их и отображаем.
        Иначе выкинем error
         */
        async register(form) {
            this.errors = {};
            try {
                await axios.get('/sanctum/csrf-cookie');
                const {data} = await axios.post('/register', form);
                this.user = data;
                return true;
            } catch (error) {
                if (error.response?.status === 422) {
                    this.errors = error.response.data.errors;
                    return false;
                }
                throw error;
            }
        },

        async logout() {
            await axios.post('/logout');
            this.user = null;
        },
    },
})
