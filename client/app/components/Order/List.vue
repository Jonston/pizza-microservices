<script setup lang="ts">
import {type Order, type OrderItem, OrderStatus, type Product, getOrderStatusColor} from '~/types/common';

const orderStore = useOrderStore();

await useAsyncData(() => {
  return orderStore.fetchOrders();
});

const getOrderTotal = computed(() => {
  return (order: Order) => {
    return order.items.reduce((total, item: OrderItem) => {
      return total + (item.product.price * item.quantity);
    }, 0);
  }
});
</script>

<template>
  <div class="order-list p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-xl font-semibold mb-4">Your Orders</h2>
    <div v-if="orderStore.orders.length > 0">
      <div v-for="order in orderStore.orders" :key="order.id" class="mb-3 p-3 border border-gray-200 rounded">
        <div class="flex justify-between">
          <span class="font-medium">Order #{{ order.id }}</span>
          <UBadge
            :color="getOrderStatusColor(order.status)"
            variant="subtle"
            size="lg"
          >
            {{ order.status }}
          </UBadge>
        </div>
        <div class="text-sm text-gray-600 mt-1">
          <div class="font-medium">
            <span class="mr-1">Items:</span>
            <template v-for="(item, index) in order.items" :key="index">
              <span>{{ item.product.name }} ({{ item.quantity }})</span>{{ index < order.items.length - 1 ? ', ' : '' }}
            </template>
          </div>
          <div class="font-medium mt-1">Total: <span class="font-bold">{{ getOrderTotal(order) }} ₴</span></div>
        </div>
      </div>
    </div>
    <div v-else class="text-gray-500 text-center py-4">
      No orders yet
    </div>
  </div>
</template>

<style scoped>
.order-list {
  min-height: 300px;
  width: 100%;
}
</style>