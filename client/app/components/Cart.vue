<script setup lang="ts">
import { useCartStore } from '../stores/cart';

// Use the cart store
const cartStore = useCartStore();

// Checkout function
function checkout() {
  console.log('Proceeding to checkout with items:', cartStore.items);
}
</script>

<template>
  <div class="cart p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-xl font-semibold mb-4">Your Cart</h2>
    
    <div v-if="cartStore.items.length > 0">
      <div v-for="item in cartStore.items" :key="item.id" class="flex justify-between items-center mb-3 pb-2 border-b border-gray-200">
        <div>
          <div class="font-medium">{{ item.name }}</div>
          <div class="text-sm text-gray-600">₽{{ item.price }} × {{ item.quantity }}</div>
        </div>
        <div class="flex items-center">
          <div class="flex items-center mr-3">
            <button 
              @click="cartStore.decreaseQuantity(item.id)" 
              class="w-8 h-8 flex items-center justify-center bg-gray-100 hover:bg-gray-200 font-bold"
              :disabled="item.quantity <= 1"
              :class="{ 'opacity-50 cursor-not-allowed': item.quantity <= 1 }"
            >
              <UIcon name="i-heroicons-minus" />
            </button>
            <span class="px-3 py-1 bg-gray-50">{{ item.quantity }}</span>
            <button 
              @click="cartStore.increaseQuantity(item.id)" 
              class="w-8 h-8 flex items-center justify-center bg-gray-100 hover:bg-gray-200 font-bold"
              :disabled="item.quantity >= 10"
              :class="{ 'opacity-50 cursor-not-allowed': item.quantity >= 10 }"
            >
              <UIcon name="i-heroicons-plus" />
            </button>
          </div>
          <div class="font-medium mr-3">₽{{ item.price * item.quantity }}</div>
          <button @click="cartStore.removeItem(item.id)" class="text-red-500 hover:text-red-700 text-xl p-1 cursor-pointer">
            <span class="text-xl">×</span>
          </button>
        </div>
      </div>
      
      <div class="flex justify-between font-semibold text-lg mt-4 pt-2">
        <span>Total:</span>
        <span>₽{{ cartStore.totalPrice }}</span>
      </div>
      
      <button @click="checkout" class="w-full mt-4 bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded">
        Checkout
      </button>
    </div>
    
    <div v-else class="text-gray-500 text-center py-4">
      Your cart is empty
    </div>
  </div>
</template>

<style scoped>
.cart {
  min-height: 300px;
  width: 100%;
}
</style>