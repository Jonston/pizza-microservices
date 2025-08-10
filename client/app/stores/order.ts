import {defineStore, acceptHMRUpdate} from 'pinia';
import {ref, computed} from 'vue';
import {OrderStatus} from '~/types/common';
import type {Order} from '~/types/common';

export const useOrderStore = defineStore('order', () => {
    const baseUrl = import.meta.server ?
        useRuntimeConfig().ordersBaseUrl :
        useRuntimeConfig().public.ordersBaseUrl;

    const orders = ref<Order[]>([]);

    const orderCount = computed(() => {
        return orders.value.length;
    });

    const getOrderById = (orderId: number) => {
        return orders.value.find(order => order.id === orderId);
    };

    const createOrder = async (data) => {
        const {data: order, error} = await $fetch('/orders', {
            method: 'POST',
            baseURL: useRuntimeConfig().public.ordersServiceBaseUrl,
            body: data
        });

        if (error.value) {
            throw new Error(`Failed to create order: ${error.value.message}`);
        }

        return order.value as Order;
    }

    const fetchOrders = async () => {
        const {data, error} = await useFetch('/orders', {
            baseURL: baseUrl,
        });

        if (data.value) {
            orders.value = data.value as Order[];
        }
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
        createOrder,
        fetchOrders
    };
});

if (import.meta.hot) {
    import.meta.hot.accept(acceptHMRUpdate(useOrderStore, import.meta.hot))
}
