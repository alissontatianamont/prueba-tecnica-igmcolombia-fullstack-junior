import './bootstrap';
import { createApp } from 'vue';
import { createPinia } from 'pinia';
import router from './router';
import App from './views/App.vue';
import api from './services/api';

const app = createApp(App);
app.config.globalProperties.$api = api;

app.use(createPinia());
app.use(router);
app.mount('#app');

