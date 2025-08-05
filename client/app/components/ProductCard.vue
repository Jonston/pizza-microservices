<script setup lang="ts">
import { useCartStore } from '../stores/cart';
import { computed } from 'vue';

const props = defineProps<{
  image: string;
  name: string;
  id: number;
  price: number;
}>();

const emit = defineEmits(['order']);

const cartStore = useCartStore();

const itemInCart = computed(() => {
  return cartStore.items.find(item => item.id === props.id);
});

const isMaxQuantity = computed(() => {
  return itemInCart.value && itemInCart.value.quantity >= 10;
});

function orderProduct() {
  emit('order');
}
</script>

<template>
  <UCard
      class="w-[300px] shadow-md"
      :ui="{ header: 'p-0 sm:p-0' }"
  >
    <template #header>
      <img :src="image" :alt="name" class="w-full h-[200px] object-cover" />
    </template>
    <div class="flex flex-col items-center gap-2 pt-0">
      <h3 class="text-xl font-semibold text-gray-700 text-center m-0">{{ name }}</h3>
      <p class="text-lg font-semibold text-gray-700 m-0">{{ price }} &#8372;</p>
      <UButton
          size="lg"
          class="uppercase"
          color="secondary"
          :disabled="isMaxQuantity"
          @click="orderProduct"
      >Order</UButton>
    </div>
  </UCard>
</template>

<style scoped>
/* All styles replaced with Tailwind utility classes */
</style>