<script lang="ts" setup>
import {inject} from "vue";
import RecipeListItem from "@/components/Recipe/List/RecipeListItem.vue";
import type {Api} from "@/Flexhub.api";

const flexhub = inject('flexhubApi') as Api<unknown>;

const response = await flexhub.api.recipeList();
const recipes = response.data;

</script>

<template>
  <RecipeListItem v-for="recipe in recipes" :key="recipe.packageName+recipe.vendor+recipe.versions.join()" :recipe="recipe"/>
</template>

<style scoped>

</style>
