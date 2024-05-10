<script lang="ts" setup>
import InputList from "@/components/Common/InputList.vue";
import KeyValueInputList from "@/components/Common/KeyValueInputList.vue";
import type {Manifest} from "@/Flexhub.api";

const manifest = defineModel<Manifest>({required: true})
</script>

<template>
  <BFormRow class="mb-2">
    <BFormGroup class="col-9" label="Bundle class">
      <BFormInput v-model="manifest.bundleClass" required></BFormInput>
    </BFormGroup>

    <BFormGroup class="col-3" label="Bundle env">
      <BFormInput v-model="manifest.bundleEnv" required/>
    </BFormGroup>
  </BFormRow>

  <div class="pb-2">
    <KeyValueInputList v-model="manifest.copyFromRecipe" key-input-placeholder="From" title="Copy from recipe"
                       value-input-placeholder="To"/>
  </div>

  <div class="pb-2">
    <KeyValueInputList v-model="manifest.copyFromPackage" key-input-placeholder="From"
                       title="Copy from package"
                       value-input-placeholder="To"/>
  </div>

  <div class="pb-2">
    <KeyValueInputList v-model="manifest.env" key-input-placeholder="Variable" title="Env"
                       value-input-placeholder="Value"/>
  </div>

  <div class="pb-2">
    <InputList v-model="manifest.gitignore" item-input-placeholder="Path" title="Git ignore"/>
  </div>

  <div class="pb-2">
    <KeyValueInputList v-model="manifest.composerScripts" key-input-placeholder="Script"
                       title="Composer scripts"
                       value-input-placeholder="Script type"/>
  </div>
</template>

<style scoped>

</style>
