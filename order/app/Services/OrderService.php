<?php
namespace App\Services;

use App\Enums\OrderStatusEnum;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Jonston\AmqpLaravel\AMQPService;

class OrderService
{
    protected AMQPService $amqpService;

    public function __construct(AMQPService $amqpService)
    {
        $this->amqpService = $amqpService;
    }

    public function create(array $data): Order
    {
        return DB::transaction(function () use ($data) {
            /* @var Order $order */
            $order = Order::create([
                'status' => OrderStatusEnum::PENDING,
            ]);

            foreach ($data['items'] as $items) {
                $order->products()->attach(
                    $items['product_id'],
                    [
                        'quantity' => $items['quantity']
                    ]
                );
            }

            $message = json_encode([
                'id' => $order->id,
                'status' => $order->status,
            ]);

            $this->amqpService->publish('orders_fanout', '', $message, 'fanout');

            $order->load('products');

            return $order;
        });
    }

    public function process(string $id): void
    {
        DB::transaction(function () use ($id) {
            sleep(3);

            $order = Order::findOrFail($id);
            $order->status = OrderStatusEnum::PROCESSING;
            $order->save();

            $data = json_encode([
                'id' => $order->id,
                'status' => $order->status,
            ]);

            $params = [
                'exchange' => 'order',
                'exchange_type' => 'fanout',
            ];

            Amqp::publish('', $data, $params);
        });
    }

    public function delivery(string $id): void
    {
        DB::transaction(function () use ($id) {
            sleep(3);

            $order = Order::findOrFail($id);
            $order->status = OrderStatusEnum::COMPLETED;
            $order->save();

            $data = json_encode([
                'id' => $order->id,
                'status' => $order->status,
            ]);

            $params = [
                'exchange' => 'order',
                'exchange_type' => 'fanout',
            ];

            Amqp::publish('', $data, $params);
        });
    }

    public function complete(string $id): void
    {
        DB::transaction(function () use ($id) {
            sleep(3);

            $order = Order::findOrFail($id);
            $order->status = OrderStatusEnum::COMPLETED;
            $order->save();

            $data = json_encode([
                'id' => $order->id,
                'status' => $order->status,
            ]);

            $params = [
                'exchange' => 'order',
                'exchange_type' => 'fanout',
            ];

            Amqp::publish('', $data, $params);
        });
    }
}