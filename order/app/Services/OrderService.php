<?php
namespace App\Services;

use App\Enums\OrderEventEnum;
use App\Enums\OrderStatusEnum;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jonston\AmqpLaravel\AMQPService;

class OrderService
{
    protected AMQPService $amqpService;

    public function __construct(AMQPService $amqpService)
    {
        $this->amqpService = $amqpService;
    }

    public function truncate(): void
    {
        DB::table('orders')->truncate();
    }

    public function create(array $data): Order
    {
        return DB::transaction(function () use ($data) {
            /* @var Order $order */
            $order = Order::create([
                'status' => OrderStatusEnum::PENDING,
            ]);

            foreach ($data['items'] as $items) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $items['product_id'],
                    'quantity' => $items['quantity']
                ]);
            }

            $message = json_encode([
                'event' => OrderEventEnum::ORDER_CREATED->value,
                'order' => [
                    'id' => $order->id,
                    'status' => $order->status,
                ]
            ]);

            Log::channel('amqp')->debug('Before publish', [
                'message' => $message,
            ]);

            $this->amqpService->publish(
                exchange: 'order_exchange',
                routingKey: 'order.created',
                message: $message
            );

            $order->load('items');

            return $order;
        });
    }

    public function process(string $id): Order
    {
        return DB::transaction(function () use ($id) {
            sleep(mt_rand(10, 60));

            /* @var Order $order */
            $order = Order::findOrFail($id);
            $order->status = OrderStatusEnum::PROCESSING;
            $order->save();

            $message = json_encode([
                'event' => OrderEventEnum::ORDER_PROCESSED->value,
                'order' => [
                    'id' => $order->id,
                    'status' => $order->status,
                ]
            ]);

            $this->amqpService->publish(
                exchange: 'order_exchange',
                routingKey: 'order.processed',
                message: $message
            );

            $order->load('items');

            return $order;
        });
    }

    public function delivery(string $id): Order
    {
        return DB::transaction(function () use ($id) {
            sleep(mt_rand(10, 60));

            /* @var Order $order */
            $order = Order::findOrFail($id);
            $order->status = OrderStatusEnum::DELIVERING;
            $order->save();

            $message = json_encode([
                'event' => OrderEventEnum::ORDER_DELIVERED->value,
                'order' => [
                    'id' => $order->id,
                    'status' => $order->status,
                ]
            ]);

            $this->amqpService->publish(
                exchange: 'order_exchange',
                routingKey: 'order.delivered',
                message: $message
            );

            $order->load('items');

            return $order;
        });
    }

    public function complete(string $id): Order
    {
        return DB::transaction(function () use ($id) {
            sleep(mt_rand(10, 60));

            /* @var Order $order */
            $order = Order::findOrFail($id);
            $order->status = OrderStatusEnum::COMPLETED;
            $order->save();

            $data = json_encode([
                'event' => OrderEventEnum::ORDER_COMPLETED->value,
                'order' => [
                    'id' => $order->id,
                    'status' => $order->status,
                ]
            ]);

            $this->amqpService->publish(
                exchange: 'order_exchange',
                routingKey: 'order.completed',
                message: $data
            );

            $order->load('items');

            return $order;
        });
    }
}