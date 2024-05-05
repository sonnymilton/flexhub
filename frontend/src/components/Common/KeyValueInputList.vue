<script lang="ts" setup>

import {ref} from "vue";

class KeyValuePair<T> {
  public key: string = '';
  public value: T;

  constructor(key: string, value: T) {
    this.key = key;
    this.value = value;
  }
}

class KeyValuePairCollection<T> extends Array<KeyValuePair<T>> {
  public static fromRecord<T>(record: Record<string, T>): KeyValuePairCollection<T> {
    const collection: KeyValuePairCollection<T> = new KeyValuePairCollection();

    for (const [key, value] of Object.entries(record)) {
      collection.push(new KeyValuePair<T>(key, value))
    }

    return collection;
  }

  public toRecord(): Record<string, T> {
    const result: Record<string, T> = {};

    for (const x of this) {
      result[x.key] = x.value;
    }

    return result;
  }

  public containsValidMap(): boolean {
    return [...this.keys()].length === this.length;
  }

  public isKeyUnique(key: string): boolean {
    const keys = this.map(item => item.key);

    return keys.filter(x => key === x).length > 1;
  }
}

interface Props {
  title?: string;
  keyInputPlaceholder: string;
  valueInputPlaceholder: string;
}

const props = withDefaults(defineProps<Props>(), {
  keyInputPlaceholder: 'Key',
  valueInputPlaceholder: 'Value',
})

const model = defineModel<Record<string, string>>({required: true})
const collection = ref(KeyValuePairCollection.fromRecord(model.value));

function onChange() {
  if (!collection.value.containsValidMap()) {
    return;
  }

  model.value = collection.value.toRecord();
}

</script>

<template>
  <BCard>
    <BCardBody>
      <BCardTitle v-if="props.title" tag="h5">{{ props.title }}</BCardTitle>

      <BRow v-for="(item, index) in collection" :key="index">
        <BFormRow class="pb-1">
          <BFormGroup class="col-5">
            <BFormInput v-model="item.key" :placeholder="props.keyInputPlaceholder"
                        :state="!collection.isKeyUnique(item.key) && !!item.key" @change="onChange"/>
            <BFormInvalidFeedback class="">Key should be unique and not empty</BFormInvalidFeedback>
          </BFormGroup>

          <BFormGroup class="col-5">
            <BFormInput v-model="item.value" :placeholder="props.valueInputPlaceholder" class="form-control col-5"
                        @change="onChange"/>
          </BFormGroup>

          <BFormGroup class="col-2">
            <BButton variant="outline-danger" @click="() => collection.splice(index, 1)">Remove</BButton>
          </BFormGroup>
        </BFormRow>
      </BRow>

      <BButton class="col-sm-3" outline variant="outline-light" @click="() => collection.push(new KeyValuePair<string>('', ''))">
        Add
      </BButton>
    </BCardBody>
  </BCard>
</template>

<style scoped>

</style>
