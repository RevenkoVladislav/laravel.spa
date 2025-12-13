import { defineStore } from 'pinia'
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
                const { data } = await axios.get('/api/user');
                this.user = data;
            } catch {
                this.user = null;
            } finally {
                this.checked = true;
            }
        },

        //получаем csrf cookie, пробуем авторизоваться
        async login(form) {
            //обнуляем ошибки
            this.errors = {}

            try {
                await axios.get('/sanctum/csrf-cookie');
                const { data } = await axios.post('/login', form);
                this.user = data;

                //возврат true или false, необходим для проверок наличия ошибок и
                //вывода валидационных ошибок в UI пользователю
                return true;

                //если ошибка валидации то ловим данные и выводим сообщение. Иначе выкидываем ошибку.
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

        //получаем куки, отправляем запрос на регистрацию
        async register(form) {
            // обнуляем ошибки
            this.errors = {};

            try {
                await axios.get('/sanctum/csrf-cookie');
                const { data } = await axios.post('/register', form);
                this.user = data;
                //возврат true или false, необходим для проверок наличия ошибок и
                //вывода валидационных ошибок в UI пользователю
                return true;
            } catch (error) {
                //если ошибки связаны с валидацией, то получаем их и отображаем. Иначе выкинем error
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
