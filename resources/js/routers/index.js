import { createRouter, createWebHistory } from 'vue-router';

import menu            from '../components/menu/main-menu.vue';
import slug            from '../components/menu/slug.vue';
import administrar     from '../components/admin/administrar.vue';
import login           from '../components/auth/login.vue';

const routes = [
  { path: '/ver/:slug',       component: slug },
  { path: '/administrar',     component: administrar },
  { path: '/iniciar-sesion',  component: login },
  { path: '/',                component: menu },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
