<script lang="ts" setup>

interface Props {
  items: Record<string, string>
}

const props = defineProps<Props>();

const isEmpty = Object.keys(props.items).length === 0;

</script>

<template>
  <span v-if="isEmpty">&ndash;</span>
  <BRow v-else>
    <BTableSimple bordered class="max-width-fit-content text-nowrap">
      <BTbody v-for="[key, value] in Object.entries(items)" :key="key">
        <BTr>
          <BTd>{{ key }}</BTd>
          <BTd>{{ value }}</BTd>
        </BTr>
      </BTbody>
    </BTableSimple>
  </BRow>
</template>

<style scoped>

</style>
