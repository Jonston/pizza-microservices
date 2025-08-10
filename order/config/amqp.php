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
        'orders_exchange' => [
            'type' => 'fanout',
            'durable' => true,
            'auto_delete' => false,
        ],
        'catalog_exchange' => [
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
        'orders.created' => [
            'durable' => true,
            'auto_delete' => false,
            'exchange' => 'orders_exchange',
            'routing_key' => '',
        ],
        'orders.processed' => [
            'durable' => true,
            'auto_delete' => false,
            'exchange' => 'orders_exchange',
            'routing_key' => '',
        ],
        'orders.delivered' => [
            'durable' => true,
            'auto_delete' => false,
            'exchange' => 'orders_exchange',
            'routing_key' => '',
        ],
        'orders.completed' => [
            'durable' => true,
            'auto_delete' => false,
            'exchange' => 'orders_exchange',
            'routing_key' => '',
        ],
        'orders.canceled' => [
            'durable' => true,
            'auto_delete' => false,
            'exchange' => 'orders_exchange',
            'routing_key' => '',
        ],
        'products.created' => [
            'durable' => true,
            'auto_delete' => false,
            'exchange' => 'catalog_exchange',
            'routing_key' => 'products.created',
        ],
        'products.truncated' => [
            'durable' => true,
            'auto_delete' => false,
            'exchange' => 'catalog_exchange',
            'routing_key' => 'products.truncated',
        ],
    ],

];