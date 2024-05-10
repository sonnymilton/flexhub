<script lang="ts" setup>
import type {RecipeListItem} from "@/Flexhub.api";

interface Props {
  recipe: RecipeListItem
}

defineProps<Props>();

function createRouteLink(recipe: RecipeListItem, version: string) {
  return {
    name: 'show_recipe',
    params: {
      version,
      vendor: recipe.vendor,
      packageName: recipe.packageName,
    }
  }
}

</script>

<template>
  <BRow>
    <BCol cols="12">
      <BCard>
        <BCardBody>
          <BRow>
            <BCol cols="12">
              <BCardTitle tag="h2">
                {{ recipe.vendor }}/{{ recipe.packageName }}
              </BCardTitle>
            </BCol>
            <BCol cols="12">
              <BRow class="align-items-center">
                <BCol cols="2">
                  Versions:
                </BCol>
                <BCol cols="10">
                  <BButtonGroup>
                    <BButton v-for="version in recipe.versions" :key="version" :to="createRouteLink(recipe, version)"
                             variant="outline-light">
                      {{ version }}
                    </BButton>
                  </BButtonGroup>
                </BCol>
              </BRow>
            </BCol>
          </BRow>
        </BCardBody>
      </BCard>
    </BCol>
  </BRow>
</template>

<style scoped>

</style>
