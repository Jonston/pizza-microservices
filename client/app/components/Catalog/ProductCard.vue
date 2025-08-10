<script setup lang="ts">
import { computed } from 'vue';
import type { Product } from "~/types/common";

const props = defineProps<{
  product: Product;
}>();

const emit = defineEmits<{
  order: [product: Product];
}>();

const cartStore = useCartStore();

const itemInCart = computed(() => {
  return cartStore.items.find(item => item.id === props.product.id);
});

const isMaxQuantity = computed(() => {
  return itemInCart.value && itemInCart.value.quantity >= 10;
});

function order() {
  emit('order', props.product);
}
</script>

<template>
  <UCard
      class="w-[300px] shadow-md"
      :ui="{ header: 'p-0 sm:p-0' }"
  >
    <template #header>
      <img
          :src="product.image"
          :alt="product.name"
          class="w-full h-[200px] object-cover"
      />
    </template>
    <div class="flex flex-col items-center gap-2 pt-0">
      <h3 class="text-xl font-semibold text-gray-700 text-center m-0">
        {{ product.name }}
      </h3>
      <p class="text-lg font-semibold text-gray-700 m-0">
        {{ product.price }} &#8372;
      </p>
      <UButton
          size="lg"
          class="uppercase"
          color="secondary"
          :disabled="isMaxQuantity"
          @click="order"
      >
        Order
      </UButton>
    </div>
  </UCard>
</template>

<style scoped>
/* All styles replaced with Tailwind utility classes */
</style>