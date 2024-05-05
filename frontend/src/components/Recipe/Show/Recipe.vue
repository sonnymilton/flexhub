<script lang="ts" setup>

import {inject, ref} from "vue";
import type {Axios, AxiosResponse} from "axios";
import type {Recipe} from "@/Models";
import KeyValueList from "@/components/Common/KeyValueList.vue";
import {useToast} from "bootstrap-vue-next";
import {useRouter} from "vue-router";

interface Props {
  vendor: string,
  packageName: string,
  version: string
}

const props = defineProps<Props>()
const axios = inject('axios') as Axios;
const router = useRouter();
const {show} = useToast();


const response: AxiosResponse<Recipe> = await axios.get<Recipe>(`/api/recipe/${props.vendor}/${props.packageName}/${props.version}/`)
const recipe: Recipe = response.data;

const loading = ref(false);

async function deleteRecipe(recipe: Recipe) {
  loading.value = true;

  try {
    await axios.delete(`/api/recipe/${recipe.vendor}/${recipe.packageName}/${recipe.version}`)

    show?.({
      props: {
        title: 'The recipe was successfully removed',
        variant: 'success',
      }
    });

    await router.push({name: 'home'});
  } catch {
    show?.({
      props: {
        title: 'Unable to delete the recipe.',
        variant: 'warning',
      }
    })
  }
  finally {
    loading.value = false;
  }

}

</script>

<template>
  <BTabs>
    <BTab title="Recipe">
      <BRow class="p-2" tag="dl">
        <BCol cols="2" tag="dt">Vendor</BCol>
        <BCol cols="10" tag="dd">{{ recipe.vendor }}</BCol>
        <BCol cols="2" tag="dt">Package name</BCol>
        <BCol cols="10" tag="dd">{{ recipe.packageName }}</BCol>
        <BCol cols="2" tag="dt">Version</BCol>
        <BCol cols="10" tag="dd">{{ recipe.version }}</BCol>
      </BRow>
    </BTab>
    <BTab title="Manifest">
      <BRow class="p-2" tag="dl">
        <BCol cols="2" tag="dt">Bundle class</BCol>
        <BCol cols="10" tag="dd">{{ recipe.manifest.bundleClass }}</BCol>

        <BCol cols="2" tag="dt">Bundle env</BCol>
        <BCol cols="10" tag="dd">{{ recipe.manifest.bundleEnv }}</BCol>

        <BCol cols="2" tag="dt">Copy from recipe</BCol>
        <BCol cols="10" tag="dd">
          <KeyValueList :items="recipe.manifest.copyFromRecipe"/>
        </BCol>

        <BCol cols="2" tag="dt">Copy from package</BCol>
        <BCol cols="10" tag="dd">
          <KeyValueList :items="recipe.manifest.copyFromPackage"/>
        </BCol>

        <BCol cols="2" tag="dt">Environment variables</BCol>
        <BCol cols="10" tag="dd">
          <KeyValueList :items="recipe.manifest.env"/>
        </BCol>

        <BCol cols="2" tag="dt">Composer scripts</BCol>
        <BCol cols="10" tag="dd">
          <KeyValueList :items="recipe.manifest.composerScripts"/>
        </BCol>

        <BCol cols="2" tag="dt">Git ignore paths</BCol>
        <BCol cols="10" tag="dd">
          <span v-if="recipe.manifest.gitignore.length === 0">&ndash;</span>
          <BListGroup>
            <BListGroupItem v-for="path in recipe.manifest.gitignore" :key="path">{{ path }}</BListGroupItem>
          </BListGroup>
        </BCol>
      </BRow>
    </BTab>
    <BTab title="Files">
      <span v-if="!recipe.files.length">Recipe does not contain files</span>
      <BAccordion>
        <BAccordionItem v-for="file in recipe.files" :key="file.path" :title="file.path">
          <BRow class="p-2" tag="dl">
            <BCol cols="2" tag="dt">File path</BCol>
            <BCol cols="4" tag="dd">{{ file.path }}</BCol>

            <BCol cols="2" tag="dt">Executable</BCol>
            <BCol cols="4" tag="dd">{{ file.executable ? 'Yes' : 'No' }}</BCol>
          </BRow>
          <BRow class="p2">
            <BCol cols="12">
              <b>File content</b>
            </BCol>
            <BCol cols="12">
              <BCard>
                <BCardBody>
                  <pre>{{ file.content }}</pre>
                </BCardBody>
              </BCard>
            </BCol>
          </BRow>
        </BAccordionItem>
      </BAccordion>
    </BTab>
    <BTab title="Actions">
      <BRow class="p-2">
        <BCol>
          <BButton :disabled="loading" variant="outline-danger" @click="deleteRecipe(recipe)">
            <BSpinner v-if="loading" small variant="danger"/>
            Delete
          </BButton>
        </BCol>
      </BRow>
    </BTab>
  </BTabs>
</template>

<style scoped>

</style>
