import { defineStore } from 'pinia';
import { ref, computed } from 'vue';

interface CartItem {
  id: number;
  name: string;
  price: number;
  quantity: number;
}

export const useCartStore = defineStore('cart', () => {
  const items = ref<CartItem[]>([
    { id: 1, name: 'Margherita Pizza', price: 250, quantity: 1 },
    { id: 3, name: 'Four Cheese Pizza', price: 300, quantity: 2 }
  ]);

  const totalPrice = computed(() => {
    return items.value.reduce((total, item) => total + (item.price * item.quantity), 0);
  });
  
  const itemCount = computed(() => {
    return items.value.length;
  });

  function addItem(item: CartItem) {
    const existingItem = items.value.find(i => i.id === item.id);

    if (existingItem) {
      if (existingItem.quantity >= 10) {
        return;
      }
      existingItem.quantity = Math.min(existingItem.quantity + 1, 10);
    } else {
      items.value.push({ ...item, quantity: 1 });
    }
  }
  
  function removeItem(itemId: number) {
    items.value = items.value.filter(item => item.id !== itemId);
  }
  
  function increaseQuantity(itemId: number) {
    const item = items.value.find(i => i.id === itemId);
    if (item && item.quantity < 10) {
      item.quantity += 1;
    }
  }
  
  function decreaseQuantity(itemId: number) {
    const item = items.value.find(i => i.id === itemId);
    if (item && item.quantity > 1) {
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
    increaseQuantity,
    decreaseQuantity,
    clearCart
  };
});

if (import.meta.hot) {
  import.meta.hot.accept(acceptHMRUpdate(useCartStore, import.meta.hot))
}
