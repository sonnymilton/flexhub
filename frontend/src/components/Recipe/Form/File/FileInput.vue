<script lang="ts" setup>
import type {File} from "@/Flexhub.api";

const model = defineModel<File>({required: true})
</script>

<template>
  <BContainer>
    <BFormRow>
      <BFormGroup class="col-8" label="File pathname">
        <BFormInput v-model="model.path"/>
      </BFormGroup>

      <BFormGroup class="col-4" label="Executable">
        <BFormCheckbox v-model="model.executable"/>
      </BFormGroup>
    </BFormRow>

    <BFormRow>
      <BFormGroup class="col-12" label="Content">
        <BFormTextarea v-model="model.content" rows="20"></BFormTextarea>
      </BFormGroup>
    </BFormRow>
  </BContainer>
</template>

<style scoped>

</style>
