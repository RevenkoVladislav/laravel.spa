import { defineStore } from 'pinia'
import axios from 'axios'

axios.defaults.withCredentials = true
axios.defaults.baseURL = '/'

//Создаю глобальный store с именем auth
export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: null,
        checked: false,
    }),
    actions: {
        async fetchUser() {
            try {
                const { data } = await axios.get('/api/user')
                this.user = data
            } catch {
                this.user = null
            } finally {
                this.checked = true
            }
        },
        async login(form) {
            await axios.get('/sanctum/csrf-cookie')
            const { data } = await axios.post('/login', form)
            this.user = data
        },
        async register(form) {
            await axios.get('/sanctum/csrf-cookie')
            const { data } = await axios.post('/register', form)
            this.user = data
        },
        async logout() {
            await axios.post('/logout')
            this.user = null
        },
    },
})
