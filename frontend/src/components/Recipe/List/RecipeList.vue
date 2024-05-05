<script lang="ts" setup>
import {inject} from "vue";
import type {Axios} from "axios";
import type {RecipeListItem as RecipeListItemInterface} from "@/Models";
import RecipeListItem from "@/components/Recipe/List/RecipeListItem.vue";

const axios = inject<Axios>('axios') as Axios;

const response = await axios.get('/api/recipe/');
const recipes = response.data as Array<RecipeListItemInterface>;

</script>

<template>
  <RecipeListItem v-for="recipe in recipes" :key="recipe.packageName+recipe.vendor+recipe.versions.join()" :recipe="recipe"/>
</template>

<style scoped>

</style>
