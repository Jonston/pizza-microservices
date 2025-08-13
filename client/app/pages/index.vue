<script setup lang="ts">

definePageMeta({
  layout: 'default'
});

const cartStore = useCartStore();
const orderStore = useOrderStore();

const addToCart = (product) => {
  cartStore.addItem({
    id: product.id,
    name: product.name,
    price: product.price,
    quantity: 1,
  });
};

const checkout = () => {
  orderStore.createOrder(cartStore.items);
  cartStore.clearCart();
};
</script>

<template>
  <div class="max-w-6xl mx-auto p-2">
    <Notification/>
    <h1 class="text-center text-2xl font-semibold mb-3">Pizza Delivery</h1>

    <Catalog
        @order="addToCart"
    />

    <div class="flex flex-col md:flex-row gap-6 mt-8">
      <div class="w-full md:w-1/2">
        <OrderList />
      </div>
      <div class="w-full md:w-1/2">
        <Cart @checkout="checkout"/>
      </div>
    </div>
  </div>
</template>

<style scoped>
</style>