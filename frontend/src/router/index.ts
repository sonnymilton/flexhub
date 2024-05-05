import {createRouter, createWebHistory} from 'vue-router'
import HomeView from '../views/HomeView.vue'
import RecipeFormView from '@/views/RecipeFormView.vue';
import RecipeView from "@/views/RecipeView.vue";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView
    },
    {
      path: '/create',
      name: 'create',
      component: RecipeFormView
    },
    {
      path: '/:vendor/:packageName/:version',
      name: 'show_recipe',
      component: RecipeView,
    },
  ]
})

export default router
