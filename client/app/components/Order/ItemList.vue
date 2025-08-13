<script setup lang="ts">
const items = [
  { name: 'Product 1', quantity: 2 },
  { name: 'Product 1', quantity: 2 },
  { name: 'Product 1', quantity: 2 }
];
</script>

<template>
  <div>
    Items:
    <template v-for="(item, index) in items" :key="index">
      {{ item.name }} <b>({{ item.quantity }})</b>
      {{ index < items.length - 1 ? ', ' : '' }}
    </template>
  </div>
</template>

<style scoped>

</style>