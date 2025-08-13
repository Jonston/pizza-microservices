<script setup lang="ts">
const orders = ref([
  {
    id: 1,
    items: [
      {
        id: 1,
        name: 'Product A',
        quantity: 2,
        price: 100
      },
      {
        id: 2,
        name: 'Product B',
        quantity: 1,
        price: 200
      }
    ],
  },
  {
    id: 2,
    items: [
      {
        id: 3,
        name: 'Product C',
        quantity: 2,
        price: 100
      },
    ],
  }
]);

const total = computed(() => {
  return (order) => {
    return order.items.reduce((sum, item) => sum + item.price * item.quantity, 0);
  };
});

onMounted(() => {

  setTimeout(() => {
    orders.value[0].items.push({
      id: 3,
      name: 'Product C',
      quantity: 1,
      price: 150
    });
  }, 2000);

  setTimeout(() => {
    orders.value[1].items[0].quantity = 20;
  }, 4000);
});
</script>

<template>
<div v-for="order in orders">Total: {{ total(order) }}</div>
</template>

<style scoped>

</style>