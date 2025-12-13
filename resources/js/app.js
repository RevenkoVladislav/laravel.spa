import './bootstrap';

import {createApp} from 'vue';
import { createPinia } from 'pinia'
import router from './router/index.js'
import App from './App.vue';

//Создание экземпляра приложения
const app = createApp(App);

//привязка pinia и router
app.use(createPinia())
app.use(router)

//привязываем приложение к id=app
app.mount('#app');
