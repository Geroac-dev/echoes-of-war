import { createVuetify } from 'vuetify';
import vuetify from './plugins/vuetify';
import { components } from "./settings";
import VueAxios from "vue-axios";
import { createApp } from 'vue';
import router from './routers';
import store from "./stores";
import axios from 'axios';
import './bootstrap';
import Principal from './components/Principal.vue';
import { createI18n } from 'vue-i18n';
import es from './lang/es';
import en from './lang/en';

const messages = {
    es: es,
    en: en
};

const i18n = createI18n({
    legacy: false,
    locale: 'es',
    fallbackLocale: 'es',
    messages,
});

axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
axios.defaults.withCredentials = true;

const token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
  axios.defaults.headers.common["X-CSRF-TOKEN"] = token.content;
}

const app = createApp(Principal);

components.forEach((e) => {
    app.component(e.name, require(`./components/${e.component}`).default);
});

app.use(router);
app.use(vuetify(i18n)); 
app.use(store);
app.use(VueAxios, axios);
app.use(i18n);
app.mount('#app');