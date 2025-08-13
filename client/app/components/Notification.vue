<script setup lang="ts">
import {getOrderStatusColor, getOrderStatusMessage, OrderStatus, OrderEvent, OrderMessage} from '~/types/common';
import {onMounted, ref} from 'vue';

const echo = useEcho();
const toast = useToast();
const orderStore = useOrderStore();
const channel = 'notifications';

const subscribe = (channel: string, event: string, callback: Function) => {
  echo.channel(channel).listen(event, callback);
};

const handler = (message: OrderMessage) => {
  console.log('Received order notification:', message);

  const order = message.data.order;

  const status = order.status as OrderStatus;
  const color = getOrderStatusColor(status);
  const text = getOrderStatusMessage(status);

  toast.add({
    title: `Заказ #${order.id}`,
    description: text,
    color: color,
    icon: 'i-heroicons-check-circle-solid',
    timeout: 5000,
  });

  orderStore.updateOrderStatus(order.id, status);
};

onMounted(() => {
  subscribe(channel, OrderEvent.CREATED, handler);
  subscribe(channel, OrderEvent.PROCESSED, handler);
  subscribe(channel, OrderEvent.DELIVERED, handler);
  subscribe(channel, OrderEvent.COMPLETED, handler);
  subscribe(channel, OrderEvent.CANCELLED, handler);
  subscribe(channel, OrderEvent.FAILED, handler);
});
</script>

<template>
  <!-- Toast'ы рендерятся глобально через useToast -->
  <div style="display:none"></div>
</template>

<style scoped>
/* нет стилей */
</style>