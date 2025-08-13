import { ORDER_STATUS_EVENTS, getOrderStatusColor, getOrderStatusMessage, OrderStatus } from '~/types/common';

// ЛОГИКА ПЕРЕНЕСЕНА В components/Notification.vue — файл оставлен только как заглушка.
// Можно удалить после обновления импортов.
export {};

let _subscribed = false;

interface OrderEventPayload {
  order: {
    id: string;
    status: OrderStatus;
  };
}

export function initOrderEvents(channelName = 'notifications') {
  if (_subscribed) return;
  _subscribed = true;

  const echo = useEcho();
  const toast = useToast();
  const orderStore = useOrderStore();

  const handle = (payload: OrderEventPayload) => {
    const { id, status } = payload.order || {};
    if (id == null || !status) {
      console.warn('Некорректное событие заказа', payload);
      return;
    }

    orderStore.updateOrderStatus(id, status);

    toast.add({
      title: getOrderStatusMessage(status),
      description: `Order #${id}`,
      color: getOrderStatusColor(status),
      timeout: 5000
    });
  };

  ORDER_STATUS_EVENTS.forEach(event =>
    echo.channel(channelName)
      .listen(event, (e: OrderEventPayload) => handle(e))
      .error(err => console.error('Order events channel error', err))
  );
}
