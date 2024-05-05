<script lang="ts" setup>
import type {File, Recipe} from "@/Models";
import Manifest from "@/components/Recipe/Form/Manifest.vue";
import FileInputList from "@/components/Recipe/Form/File/FileInputList.vue";
import {inject, type Ref, ref} from "vue";
import {type Axios, AxiosError, HttpStatusCode} from "axios";
import {useToast} from "bootstrap-vue-next";
import {useRouter} from "vue-router";


const recipe: Recipe = {
  vendor: '',
  packageName: '',
  version: '',
  manifest: {
    bundleClass: '',
    bundleEnv: '',
    copyFromPackage: {},
    copyFromRecipe: {},
    env: {},
    gitignore: new Array<string>(),
    composerScripts: {},
  },
  files: new Array<File>()
};

const recipeRef: Ref<Recipe> = ref(recipe);
const loading: Ref<boolean> = ref(false);

const axios = inject<Axios>('axios') as Axios;
const {show} = useToast();
const router = useRouter();

async function save(recipe: Recipe): Promise<void> {
  try {
    loading.value = true;

    await axios.post('/api/recipe/', recipe);
    await router.push({name: 'home'});
  } catch (error: any) {
    if (error instanceof AxiosError) {
      const response = error.response;

      if (!response) {
        throw error;
      }

      if (response.status === HttpStatusCode.UnprocessableEntity) {
        const {title, detail} = response.data;

        show?.({
          props: {
            title: title,
            body: detail,
            bodyClass: 'white-space-pre-line',
            variant: 'danger',
          }
        });
      }
    }
  } finally {
    loading.value = false;
  }
}
</script>

<template>
  <BForm>
    <h2>Recipe</h2>

    <BFormRow>
      <BFormGroup class="col-6" label="Vendor">
        <BFormInput v-model="recipe.vendor" required/>
      </BFormGroup>

      <BFormGroup class="col-6" label="Package name">
        <BFormInput v-model="recipe.packageName" required></BFormInput>
      </BFormGroup>

      <BFormGroup class="col-6" label="Version">
        <BFormInput v-model="recipe.version" required></BFormInput>
      </BFormGroup>
    </BFormRow>

    <h3>Manifest</h3>

    <Manifest v-model="recipe.manifest"/>

    <h3>Files</h3>
    <FileInputList v-model="recipeRef.files"/>

    <hr class="divider">

    <BFormRow>
      <BFormGroup class="col-2">
        <BButton :disabled="loading" class="w-100" variant="outline-primary" @click="save(recipe)">
          <BSpinner v-if="loading" small variant="primary"/>
          Save
        </BButton>
      </BFormGroup>
      <BFormGroup class="col-2">
        <BButton class="w-100" variant="outline-secondary" @click="router.back()">Back</BButton>
      </BFormGroup>
    </BFormRow>
  </BForm>
</template>
<style scoped>
</style>
