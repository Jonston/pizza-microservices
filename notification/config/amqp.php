<?php

return [

    /*
    |--------------------------------------------------------------------------
    | AMQP Connection Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for RabbitMQ/AMQP connection
    |
    */

    'host' => env('AMQP_HOST', 'localhost'),
    'port' => env('AMQP_PORT', 5672),
    'user' => env('AMQP_USER', 'guest'),
    'password' => env('AMQP_PASSWORD', 'guest'),
    'vhost' => env('AMQP_VHOST', '/'),

    /*
    |--------------------------------------------------------------------------
    | Exchanges
    |--------------------------------------------------------------------------
    |
    | Define exchanges that will be created during setup
    | Uncomment and configure as needed
    |
    */
    'exchanges' => [
        'order_exchange' => [
            'type' => 'direct',
            'durable' => true,
            'auto_delete' => false,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Queues
    |--------------------------------------------------------------------------
    |
    | Define queues that will be created and bound to exchanges
    | Uncomment and configure as needed
    |
    */
    'queues' => [
        'notification.order.created' => [
            'durable' => true,
            'auto_delete' => false,
            'exchange' => 'order_exchange',
            'routing_key' => 'order.created',
        ],
        'notification.order.processed' => [
            'durable' => true,
            'auto_delete' => false,
            'exchange' => 'order_exchange',
            'routing_key' => 'order.processed',
        ],
        'notification.order.delivered' => [
            'durable' => true,
            'auto_delete' => false,
            'exchange' => 'order_exchange',
            'routing_key' => 'order.delivered',
        ],
        'notification.order.completed' => [
            'durable' => true,
            'auto_delete' => false,
            'exchange' => 'order_exchange',
            'routing_key' => 'order.completed',
        ],
        'notification.order.cancelled' => [
            'durable' => true,
            'auto_delete' => false,
            'exchange' => 'order_exchange',
            'routing_key' => 'order.cancelled',
        ],
        'notification.order.failed' => [
            'durable' => true,
            'auto_delete' => false,
            'exchange' => 'order_exchange',
            'routing_key' => 'order.failed',
        ]
    ],

];