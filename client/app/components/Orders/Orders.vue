<script setup lang="ts">
import { OrderStatus } from '~/types/common';

const orderStore = useOrderStore();

const fetchOrders = async () => {
  try {
    await orderStore.fetchOrders();
  } catch (error) {
    console.error('Failed to fetch orders:', error);
  }
};

const getStatusColor = (status: OrderStatus) => {
  switch (status) {
    case OrderStatus.PENDING:
      return 'info';
    case OrderStatus.PROCESSING:
      return 'warning';
    case OrderStatus.DELIVERING:
      return 'warning';
    case OrderStatus.COMPLETED:
      return 'success';
    case OrderStatus.CANCELLED:
      return 'error';
    case OrderStatus.FAILED:
      return 'error';
    default:
      return 'gray';
  }
};
</script>

<template>
  <div class="order-list p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-xl font-semibold mb-4">Your Orders</h2>
    <UButton @click="fetchOrders">Refresh</UButton>
    <div v-if="orderStore.orders.length > 0">
      <div v-for="order in orderStore.orders" :key="order.id" class="mb-3 p-3 border border-gray-200 rounded">
        <div class="flex justify-between">
          <span class="font-medium">Order #{{ order.id }}</span>
          <UBadge
            :color="getStatusColor(order.status)"
            variant="subtle"
            size="lg"
          >
            {{ order.status }}
          </UBadge>
        </div>
        <div class="text-sm text-gray-600 mt-1">
          <div>Items: {{ order.items.join(', ') }}</div>
          <div class="font-medium mt-1">Total: ₽{{ order.total }}</div>
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