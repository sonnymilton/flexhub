<script lang="ts" setup>
import RecipeList from "@/components/Recipe/List/RecipeList.vue";
</script>

<template>
  <main>
    <Suspense>
      <RecipeList/>
    </Suspense>
  </main>
</template>
