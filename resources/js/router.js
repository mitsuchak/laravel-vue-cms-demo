import { createRouter, createWebHistory } from 'vue-router';
import PageContent from './components/PageContent.vue';

const routes = [
  {
    path: '/slug-pages/:slug*', // Wildcard route for nested pages
    name: 'page',
    component: PageContent,
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;