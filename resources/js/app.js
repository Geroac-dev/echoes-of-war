import vuetify from './plugins/vuetify';
import { components } from "./settings";
import VueAxios from "vue-axios";
import { createApp } from 'vue';
import router from './routers';
import store from "./stores";
import axios from 'axios';
import './bootstrap';
import Principal from './components/Principal.vue';

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
app.use(vuetify);
app.use(store);
app.use(VueAxios, axios);
app.mount('#app');
