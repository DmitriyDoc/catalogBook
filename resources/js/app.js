import "bootstrap/dist/css/bootstrap.min.css"
import { createApp } from 'vue';
import { createPinia } from "pinia";
import App from './App.vue';
import ElementPlus from 'element-plus';
import 'element-plus/dist/index.css'
import axios from 'axios'
import VueAxios from 'vue-axios'
import router from './routes'


createApp(App).use(createPinia()).use(router).use(VueAxios, axios).use(ElementPlus).mount('#app');
//import "bootstrap/dist/js/bootstrap.js"
