export enum OrderStatus {
  PENDING = 'pending',
  PROCESSING = 'processing',
  DELIVERING = 'delivering',
  COMPLETED = 'completed',
  CANCELLED = 'cancelled',
  FAILED = 'failed'
}

export interface Order {
  id: number;
  status: OrderStatus;
  items: string[];
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
