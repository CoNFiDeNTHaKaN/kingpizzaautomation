<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Define which configuration should be used
    |--------------------------------------------------------------------------
    */

    'use' => env('AMQP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | AMQP properties separated by key
    |--------------------------------------------------------------------------
    */

    'properties' => [

        'production' => [
            'host'                  => env('AMQP_HOST', 'localhost'),
            'port'                  => env('AMQP_PORT', 5672),
            'username'              => env('AMQP_USER', 'eatkebab'),
            'password'              => env('AMQP_PASSWORD', 'eatkebab'),
            'vhost'                 => env('AMQP_VHOST', '/'),
            'connect_options'       => [],
            'ssl_options'           => [],

            'exchange'              => env('RABBITMQ_EXCHANGE_NAME', 'Orders'),
            'exchange_type'         => env('RABBITMQ_EXCHANGE_TYPE', 'fanout'),
            'exchange_passive'      => env('RABBITMQ_EXCHANGE_PASSIVE', false),
            'exchange_durable'      => env('RABBITMQ_EXCHANGE_DURABLE', false),
            'exchange_auto_delete'  => env('RABBITMQ_EXCHANGE_AUTO_DELETE', false),
            'exchange_internal'     => env('RABBITMQ_EXCHANGE_INTERNAL', false),
            'exchange_nowait'       => env('RABBITMQ_EXCHANGE_NOWAIT', false),
            'exchange_properties'   => [],

            'queue_force_declare'   => env('RABBITMQ_QUEUE_FORCE_DECLARE', false),
            'queue_passive'         => env('RABBITMQ_QUEUE_PASSIVE', false),
            'queue_durable'         => env('RABBITMQ_QUEUE_DURABLE', false),
            'queue_exclusive'       => env('RABBITMQ_QUEUE_EXCLUSIVE', false),
            'queue_auto_delete'     => env('RABBITMQ_QUEUE_AUTO_DELETE', false),
            'queue_nowait'          => env('RABBITMQ_QUEUE_NOWAIT', false),
            'queue_properties'      => ['x-ha-policy' => ['S', 'all']],

            'consumer_tag'          => env('RABBITMQ_CONSUMER_TAG', ''),
            'consumer_no_local'     => env('RABBITMQ_CONSUMER_NO_LOCAL', false),
            'consumer_no_ack'       => env('RABBITMQ_CONSUMER_NO_ACK', false),
            'consumer_exclusive'    => env('RABBITMQ_CONSUMER_EXCLUSIVE', false),
            'consumer_nowait'       => env('RABBITMQ_CONSUMER_NOWAIT', false),
            'timeout'               => env('RABBITMQ_CONSUMER_TIMEOUT', 0),
            'persistent'            => env('RABBITMQ_CONSUMER_PERSISTENT', false),

            'qos'                   => false,
            'qos_prefetch_size'     => 0,
            'qos_prefetch_count'    => 1,
            'qos_a_global'          => false
        ],

    ],

];