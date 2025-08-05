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
