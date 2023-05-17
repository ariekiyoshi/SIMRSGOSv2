<?php
return [
    'service_manager' => [
        'factories' => [],
    ],
    'router' => [
        'routes' => [
            'generator.rpc.signature' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/generator/signature',
                    'defaults' => [
                        'controller' => 'Generator\\V1\\Rpc\\Signature\\Controller',
                        'action' => 'index',
                    ],
                ],
            ],
        ],
    ],
    'zf-versioning' => [
        'uri' => [
            0 => 'generator.rpc.signature',
        ],
    ],
    'zf-rest' => [],
    'zf-content-negotiation' => [
        'controllers' => [
            'Generator\\V1\\Rpc\\Signature\\Controller' => 'Json',
        ],
        'accept_whitelist' => [
            'Generator\\V1\\Rpc\\Signature\\Controller' => [
                0 => 'application/vnd.generator.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
                3 => 'application/x-www-form-urlencoded',
            ],
        ],
        'content_type_whitelist' => [
            'Generator\\V1\\Rpc\\Signature\\Controller' => [
                0 => 'application/vnd.generator.v1+json',
                1 => 'application/json',
                2 => 'application/x-www-form-urlencoded',
            ],
        ],
    ],
    'zf-hal' => [
        'metadata_map' => [],
    ],
    'controllers' => [
        'factories' => [
            'Generator\\V1\\Rpc\\Signature\\Controller' => \Generator\V1\Rpc\Signature\SignatureControllerFactory::class,
        ],
    ],
    'zf-rpc' => [
        'Generator\\V1\\Rpc\\Signature\\Controller' => [
            'service_name' => 'Signature',
            'http_methods' => [
                0 => 'GET',
                1 => 'POST',
            ],
            'route_name' => 'generator.rpc.signature',
        ],
    ],
    'view_manager' => [
        'template_map' => [
            'generator/v1/rpc/signature/signature/index' => __DIR__ . '/../view/signature/index.phtml',
        ],
        'template_path_stack' => [
            0 => __DIR__ . '/../view',
        ],
    ],
];
