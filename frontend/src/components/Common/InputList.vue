<script lang="ts" setup>
import {ref} from "vue";

interface Props {
  title?: string,
  itemInputPlaceholder?: string
}

const props = defineProps<Props>()

const model = defineModel<Array<string>>({required: true})
const collection = ref(model.value)
</script>

<template>
  <BCard>
    <BCardBody>
      <BCardTitle v-if="props.title" tag="h5">{{ props.title }}</BCardTitle>

      <BFormRow v-for="(item, index) in collection" :key="index">
        <BFormGroup class="col-5 pb-2">
          <BFormInput v-model="collection[index]"
                      :state="collection.filter(x => x === item).length === 1 && !!item"
                      :placeholder="props.itemInputPlaceholder"
          />
          <BFormInvalidFeedback>Should be unique and not empty</BFormInvalidFeedback>
        </BFormGroup>

        <BFormGroup class="col-2">
          <BButton variant="danger" @click="() => collection.splice(index, 1)">Remove</BButton>
        </BFormGroup>
      </BFormRow>

      <BButton class="col-3" variant="outline-light" type="button" @click="() => collection.push('')">Add</BButton>
    </BCardBody>
  </BCard>
</template>

<style scoped>

</style>
