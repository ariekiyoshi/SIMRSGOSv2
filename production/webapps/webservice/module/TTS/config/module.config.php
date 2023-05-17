<?php
return [
    'controllers' => [
        'factories' => [
            'TTS\\V1\\Rpc\\TTService\\Controller' => \TTS\V1\Rpc\TTService\TTServiceControllerFactory::class,
        ],
    ],
    'router' => [
        'routes' => [
            'tts.rpc.tt-service' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/tts[/:action][/:id]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[a-zA-Z0-9]+',
                    ],
                    'defaults' => [
                        'controller' => 'TTS\\V1\\Rpc\\TTService\\Controller',
                    ],
                ],
            ],
        ],
    ],
    'zf-versioning' => [
        'uri' => [
            0 => 'tts.rpc.tt-service',
        ],
    ],
    'zf-rpc' => [
        'TTS\\V1\\Rpc\\TTService\\Controller' => [
            'service_name' => 'TTService',
            'http_methods' => [
                0 => 'GET',
            ],
            'route_name' => 'tts.rpc.tt-service',
        ],
    ],
    'zf-content-negotiation' => [
        'controllers' => [
            'TTS\\V1\\Rpc\\TTService\\Controller' => 'Json',
        ],
        'accept_whitelist' => [
            'TTS\\V1\\Rpc\\TTService\\Controller' => [
                0 => 'application/vnd.tts.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ],
        ],
        'content_type_whitelist' => [
            'TTS\\V1\\Rpc\\TTService\\Controller' => [
                0 => 'application/vnd.tts.v1+json',
                1 => 'application/json',
            ],
        ],
    ],
];
