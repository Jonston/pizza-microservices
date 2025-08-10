<script setup lang="ts">
import { defineProps, defineEmits, computed } from 'vue';

import type { CartItem } from '~/types/common';

const props = defineProps<{
  item: CartItem;
}>();

const emits = defineEmits<{
  (e: 'increase', id: string | number): void;
  (e: 'decrease', id: string | number): void;
  (e: 'remove', id: string | number): void;
}>();

const cartStore = useCartStore();

const canIncrease = computed(() => !cartStore.isMaxQuantity(props.item.id));
const canDecrease = computed(() => !cartStore.isMinQuantity(props.item.id));
</script>

<template>
  <div class="flex justify-between items-center mb-3 pb-2 border-b border-gray-200">
    <div>
      <div class="font-medium">{{ item.name }}</div>
      <div class="text-sm text-gray-600">{{ item.price }} × {{ item.quantity }} ₴</div>
    </div>
    <div class="flex items-center">
      <div class="flex items-center mr-3">
        <button
          @click="emits('decrease', item.id)"
          class="w-8 h-8 flex items-center justify-center bg-gray-100 hover:bg-gray-200 font-bold"
          :disabled="!canDecrease"
          :class="{ 'opacity-50 cursor-not-allowed': !canDecrease }"
        >
          <UIcon name="i-heroicons-minus" />
        </button>
        <span class="px-3 py-1 bg-gray-50">{{ item.quantity }}</span>
        <button
          @click="emits('increase', item.id)"
          class="w-8 h-8 flex items-center justify-center bg-gray-100 hover:bg-gray-200 font-bold"
          :disabled="!canIncrease"
          :class="{ 'opacity-50 cursor-not-allowed': !canIncrease }"
        >
          <UIcon name="i-heroicons-plus" />
        </button>
      </div>
      <div class="font-medium mr-3">{{ item.price * item.quantity }} ₴</div>
      <button @click="emits('remove', item.id)" class="text-red-500 hover:text-red-700 text-xl p-1 cursor-pointer">
        <span class="text-xl">×</span>
      </button>
    </div>
  </div>
</template>

<style scoped>

</style>