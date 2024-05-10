<script lang="ts" setup>
import Manifest from "@/components/Recipe/Form/Manifest.vue";
import FileInputList from "@/components/Recipe/Form/File/FileInputList.vue";
import {inject, type Ref, ref} from "vue";
import {useToast} from "bootstrap-vue-next";
import {useRouter} from "vue-router";
import {Api, type File, type Recipe} from "@/Flexhub.api";

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

const flexhub = inject('flexhubApi') as Api<unknown>

const {show} = useToast();
const router = useRouter();

async function save(recipe: Recipe): Promise<void> {
  try {
    loading.value = true;

    await flexhub.api.recipeCreate(recipe);
    await router.push({name: 'home'});
  } catch (error: any) {
    if (error instanceof Response) {
      const response = await error.json();

      if (response.status === 422) {
        const {title, detail} = response;

        show?.({
          props: {
            title: title,
            body: detail,
            bodyClass: 'white-space-pre-line',
            variant: 'danger',
          }
        });

        return
      }

      show?.({
        props: {
          title: 'API error',
          body: 'An error occurred while interacting with the API',
          variant: 'danger',
        }
      });

      throw error;
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
