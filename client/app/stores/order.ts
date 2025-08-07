import { defineStore, acceptHMRUpdate } from 'pinia';
import { ref, computed } from 'vue';
import { OrderStatus } from '~/types/common';
import type { Order } from '~/types/common';
export const useOrderStore = defineStore('order', () => {
  const orders = ref<Order[]>([
    {
      id: 1,
      status: OrderStatus.PENDING,
      items: ['Margherita Pizza'],
      total: 250
    },
    {
      id: 2,
      status: OrderStatus.PROCESSING,
      items: ['Pepperoni Pizza'],
      total: 280
    },
    {
      id: 3,
      status: OrderStatus.DELIVERING,
      items: ['Four Cheese Pizza'],
      total: 300
    },
    {
      id: 4,
      status: OrderStatus.COMPLETED,
      items: ['Margherita Pizza', 'Pepperoni Pizza'],
      total: 530
    },
    {
      id: 5,
      status: OrderStatus.CANCELLED,
      items: ['Four Cheese Pizza'],
      total: 300
    },
    {
      id: 6,
      status: OrderStatus.FAILED,
      items: ['Pepperoni Pizza'],
      total: 280
    }
  ]);

  const orderCount = computed(() => {
    return orders.value.length;
  });
  
  const getOrderById = (orderId: number) => {
    return orders.value.find(order => order.id === orderId);
  };

  function addOrder(order: Omit<Order, 'id'>) {
    const newId = orders.value.length > 0 
      ? Math.max(...orders.value.map(o => o.id)) + 1 
      : 1;

    orders.value.push({
      id: newId,
      ...order
    });
    
    return newId;
  }
  
  function updateOrderStatus(orderId: number, status: OrderStatus) {
    const order = orders.value.find(o => o.id === orderId);
    if (order) {
      order.status = status;
    }
  }
  
  function removeOrder(orderId: number) {
    orders.value = orders.value.filter(order => order.id !== orderId);
  }
  
  return {
    orders,
    orderCount,
    getOrderById,
    addOrder,
    updateOrderStatus,
    removeOrder
  };
});

if (import.meta.hot) {
  import.meta.hot.accept(acceptHMRUpdate(useOrderStore, import.meta.hot))
}
