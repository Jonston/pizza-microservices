<script setup lang="ts">
import type {Product} from "~/types/common";

const catalogStore = useCatalogStore();

const emit = defineEmits(['order']);

const {data, refresh} = await useAsyncData(async () => {
  return await catalogStore.fetchProducts();
});

const order = (product: Product) => {
  emit('order', product);
};

</script>

<template>
  <div class="flex flex-wrap justify-center items-center gap-8 mb-8">
    <CatalogProductCard
        v-for="product in catalogStore.products"
        :key="product.id"
        :id="product.id"
        :product="product"
        @order="order(product)"
    />
  </div>
</template>

<style scoped>

</style>