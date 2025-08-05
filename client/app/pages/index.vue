<script setup lang="ts">
import { useCartStore } from '../stores/cart';

definePageMeta({
  layout: 'default'
})
const products = [
  {
    id: 1,
    name: 'Margherita Pizza',
    image: 'https://images.unsplash.com/photo-1604382354936-07c5d9983bd3?q=80&w=1000&auto=format&fit=crop',
    price: 250
  },
  {
    id: 2,
    name: 'Pepperoni Pizza',
    image: 'https://images.unsplash.com/photo-1628840042765-356cda07504e?q=80&w=1000&auto=format&fit=crop',
    price: 280
  },
  {
    id: 3,
    name: 'Four Cheese Pizza',
    image: 'https://images.unsplash.com/photo-1513104890138-7c749659a591?q=80&w=1000&auto=format&fit=crop',
    price: 300
  }
];

const cartStore = useCartStore();

function handleOrder(productId: number) {
  // Find the product by ID
  const product = products.find(p => p.id === productId);
  
  if (product) {
    // Add the product to the cart
    cartStore.addItem({
      id: product.id,
      name: product.name,
      price: product.price,
      quantity: 1
    });
  }
}
</script>

<template>
  <div class="max-w-6xl mx-auto p-2">
    <Notification />
    <h1 class="text-center text-2xl font-semibold mb-3">Pizza Delivery</h1>
    
    <div class="flex flex-wrap justify-center items-center gap-8 mb-8">
      <ProductCard 
        v-for="product in products" 
        :key="product.id"
        :id="product.id"
        :name="product.name"
        :image="product.image"
        :price="product.price"
        @order="handleOrder(product.id)"
      />
    </div>

    <div class="flex flex-col md:flex-row gap-6 mt-8">
      <div class="w-full md:w-1/2">
        <OrderList />
      </div>
      <div class="w-full md:w-1/2">
        <Cart />
      </div>
    </div>
  </div>
</template>

<style scoped>
</style>