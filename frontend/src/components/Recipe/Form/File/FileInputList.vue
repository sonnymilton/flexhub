<script lang="ts" setup>
import FileInput from "@/components/Recipe/Form/File/FileInput.vue";
import IconExecutable from "@/components/icons/IconExecutable.vue";
import {type File} from '@/Flexhub.api'

const model = defineModel<Array<File>>({required: true})

function addFile() {
  model.value.push({
    path: '/path/to/file/filename',
    executable: false,
    content: ''
  })
}

</script>

<template>
  <BRow>
    <BAccordion class="col-12 mb-2">
      <BAccordionItem v-for="(file, index) in model" :key="index" header-tag="div">
        <template #title>
          <BRow class="w-100">
            <BCol cols="10">
              <h5>
                {{ file.path }}
                <IconExecutable v-if="file.executable"/>
              </h5>
            </BCol>
            <BCol class="text-end" cols="2">
              <BButton variant="outline-danger" @click.stop="model.splice(index, 1)">Remove</BButton>
            </BCol>
          </BRow>
        </template>

        <FileInput v-model="model[index]"/>

      </BAccordionItem>
    </BAccordion>

    <BCol cols="2">
      <BButton class="w-100" variant="outline-light" @click="addFile">Add</BButton>
    </BCol>
  </BRow>
</template>

<style scoped>

</style>
