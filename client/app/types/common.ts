export enum OrderStatus {
  PENDING = 'pending',
  PROCESSING = 'processing',
  DELIVERING = 'delivering',
  COMPLETED = 'completed',
  CANCELLED = 'cancelled',
  FAILED = 'failed'
}

export enum OrderEvent {
    CREATED = '.order.created',
    PROCESSED = '.order.processed',
    DELIVERED = '.order.delivered',
    COMPLETED = '.order.completed',
    CANCELLED = '.order.cancelled',
    FAILED = '.order.failed'
}

export interface OrderPayload {
    id: string;
    status: OrderStatus;
}

export interface OrderMessage {
    data: {
        event: OrderEvent;
        order: OrderPayload
    }
}

export interface OrderItem {
    product: Product;
    quantity: number;
}

export interface Order {
  id: number;
  status: OrderStatus;
  items: OrderItem[];
  total: number;
}

export interface Product {
    id: string;
    name: string;
    price: string;
    image: string;
}

export interface CartItem {
    id: string;
    name: string;
    price: number;
    quantity: number;
}

export const getOrderStatusColor = (status: OrderStatus): string => {
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
    case OrderStatus.FAILED:
      return 'error';
    default:
      return 'gray';
  }
};

export const getOrderStatusMessage = (status: OrderStatus): string => {
  switch (status) {
    case OrderStatus.PENDING:
      return 'New order created';
    case OrderStatus.PROCESSING:
      return 'Order is being processed';
    case OrderStatus.DELIVERING:
      return 'Order is being delivered';
    case OrderStatus.COMPLETED:
      return 'Order completed';
    case OrderStatus.CANCELLED:
      return 'Order cancelled';
    case OrderStatus.FAILED:
      return 'Order failed';
    default:
      return 'Order updated';
  }
};

