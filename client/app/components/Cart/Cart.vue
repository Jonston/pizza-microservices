<script setup lang="ts">
const cartStore = useCartStore();

const emit = defineEmits(['checkout']);

function checkout() {
  emit('checkout');
}
</script>

<template>
  <div class="cart p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-xl font-semibold mb-4">Your Cart</h2>
    
    <div v-if="cartStore.items.length > 0">
      <CartItem
        v-for="item in cartStore.items"
        :key="item.id"
        :item="item"
        @increase="cartStore.increaseQuantity"
        @decrease="cartStore.decreaseQuantity"
        @remove="cartStore.removeItem"
      />

      <div class="flex justify-between font-semibold text-lg mt-4 pt-2">
        <span>Total:</span>
        <span>{{ cartStore.totalPrice }} ₴</span>
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