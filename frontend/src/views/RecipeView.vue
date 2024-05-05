<script lang="ts" setup>
import {useRoute} from "vue-router";
import Recipe from "@/components/Recipe/Show/Recipe.vue";

const route = useRoute();


</script>

<template>
  <Suspense>
    <Recipe :vendor="route.params.vendor as string" :package-name="route.params.packageName as string" :version="route.params.version as string"/>
  </Suspense>
</template>

<style scoped>

</style>
