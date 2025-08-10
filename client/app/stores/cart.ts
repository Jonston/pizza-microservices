import {defineStore} from 'pinia';
import {ref, computed} from 'vue';

type ItemId = string | number;

interface CartItem {
    id: ItemId;
    name: string;
    price: number;
    quantity: number;
}

export const useCartStore = defineStore('cart', () => {
    const items = ref<CartItem[]>([]);

    const maxQuantity = 10;
    const minQuantity = 3;

    const totalPrice = computed(() => {
        return items.value.reduce((total, item) => total + (item.price * item.quantity), 0);
    });

    const itemCount = computed(() => {
        return items.value.length;
    });

    function findItemById(itemId: ItemId) {
        return items.value.find(item => item.id === itemId);
    }

    function addItem(item: CartItem) {
        const existingItem = items.value.find(i => i.id === item.id);

        if (existingItem) {
            increaseQuantity(item.id);
        } else {
            items.value.push({
                ...item,
                quantity: minQuantity
            });
        }
    }

    function removeItem(itemId: ItemId) {
        items.value = items.value.filter(item => item.id !== itemId);
    }

    function isMaxQuantity(itemId: ItemId) {
        const item = findItemById(itemId);

        return item ? item.quantity >= maxQuantity : false;
    }

    function isMinQuantity(itemId: ItemId) {
        const item = findItemById(itemId);

        return item ? item.quantity <= minQuantity : false;
    }

    function increaseQuantity(itemId: ItemId) {
        const item = findItemById(itemId);

        if (item && ! isMaxQuantity(itemId)) {
            item.quantity += 1;
        }
    }

    function decreaseQuantity(itemId: ItemId) {
        const item = findItemById(itemId);

        if (item && ! isMinQuantity(itemId)) {
            item.quantity -= 1;
        }
    }

    function clearCart() {
        items.value = [];
    }

    return {
        items,
        totalPrice,
        itemCount,
        addItem,
        removeItem,
        isMaxQuantity,
        isMinQuantity,
        increaseQuantity,
        decreaseQuantity,
        clearCart
    };
});

if (import.meta.hot) {
    import.meta.hot.accept(acceptHMRUpdate(useCartStore, import.meta.hot))
}
