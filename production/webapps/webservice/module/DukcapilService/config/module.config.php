<?php
return [
    'controllers' => [
        'factories' => [
            'DukcapilService\\V1\\Rpc\\Dukcapil\\Controller' => \DukcapilService\V1\Rpc\Dukcapil\DukcapilControllerFactory::class,
        ],
    ],
    'router' => [
        'routes' => [
            'dukcapil-service.rpc.dukcapil' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/dukcapil/service[/:action][/:id]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[a-zA-Z0-9]+',
                    ],
                    'defaults' => [
                        'controller' => 'DukcapilService\\V1\\Rpc\\Dukcapil\\Controller',
                    ],
                ],
            ],
        ],
    ],
    'zf-versioning' => [
        'uri' => [
            0 => 'dukcapil-service.rpc.dukcapil',
        ],
    ],
    'zf-rpc' => [
        'DukcapilService\\V1\\Rpc\\Dukcapil\\Controller' => [
            'service_name' => 'Dukcapil',
            'http_methods' => [
                0 => 'GET',
            ],
            'route_name' => 'dukcapil-service.rpc.dukcapil',
        ],
    ],
    'zf-content-negotiation' => [
        'controllers' => [
            'DukcapilService\\V1\\Rpc\\Dukcapil\\Controller' => 'Json',
        ],
        'accept_whitelist' => [
            'DukcapilService\\V1\\Rpc\\Dukcapil\\Controller' => [
                0 => 'application/vnd.dukcapil-service.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ],
        ],
        'content_type_whitelist' => [
            'DukcapilService\\V1\\Rpc\\Dukcapil\\Controller' => [
                0 => 'application/vnd.dukcapil-service.v1+json',
                1 => 'application/json',
            ],
        ],
    ],
];
