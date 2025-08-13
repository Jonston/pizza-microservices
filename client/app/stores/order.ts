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

    const createOrder = async (items) => {
        const requestData = {
            items: items.map(item => ({
                product_id: item.id,
                quantity: item.quantity
            })),
        }

        const {data, error} = await $fetch('/orders', {
            method: 'POST',
            baseURL: baseUrl,
            body: requestData
        });

        if (error) {
            throw new Error(`Failed to create order: ${error.value.message}`);
        }

        console.log('Order created:', data);

        orders.value.push(data as Order);

        return data as Order;
    }

    const fetchOrders = async () => {
        const {data, error} = await $fetch('/orders', {
            baseURL: baseUrl,
        });

        if (data) {
            orders.value = data as Order[];
        }

        return data;
    }

    function updateOrderStatus(orderId: number, status: OrderStatus) {
        const order = orders.value.find(o => o.id === orderId);

        if (order) {
            order.status = status;
        }
    }

    return {
        orders,
        orderCount,
        getOrderById,
        createOrder,
        fetchOrders,
        updateOrderStatus,
    };
});

if (import.meta.hot) {
    import.meta.hot.accept(acceptHMRUpdate(useOrderStore, import.meta.hot))
}
