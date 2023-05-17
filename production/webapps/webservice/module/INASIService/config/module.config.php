<?php
return array(
    'controllers' => array(
        'factories' => array(
            'INASIService\\V1\\Rpc\\Peserta\\Controller' => 'INASIService\\V1\\Rpc\\Peserta\\PesertaControllerFactory',
            'INASIService\\V1\\Rpc\\Kunjungan\\Controller' => 'INASIService\\V1\\Rpc\\Kunjungan\\KunjunganControllerFactory',
        ),
    ),
    'router' => array(
        'routes' => array(
            'inasi-service.rpc.peserta' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/inasiservice/peserta[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'INASIService\\V1\\Rpc\\Peserta\\Controller',
                    ),
                ),
            ),
            'inasi-service.rpc.kunjungan' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/inasiservice/kunjungan[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'INASIService\\V1\\Rpc\\Kunjungan\\Controller',
                    ),
                ),
            ),
        ),
    ),
    'zf-versioning' => array(
        'uri' => array(
            0 => 'inasi-service.rpc.peserta',
            1 => 'inasi-service.rpc.kunjungan',
        ),
    ),
    'zf-rpc' => array(
        'INASIService\\V1\\Rpc\\Peserta\\Controller' => array(
            'service_name' => 'peserta',
            'http_methods' => array(
                0 => 'GET',
            ),
            'route_name' => 'inasi-service.rpc.peserta',
        ),
        'INASIService\\V1\\Rpc\\Kunjungan\\Controller' => array(
            'service_name' => 'kunjungan',
            'http_methods' => array(
                0 => 'GET',
                1 => 'POST',
            ),
            'route_name' => 'inasi-service.rpc.kunjungan',
        ),
    ),
    'zf-content-negotiation' => array(
        'controllers' => array(
            'INASIService\\V1\\Rpc\\Peserta\\Controller' => 'Json',
            'INASIService\\V1\\Rpc\\Kunjungan\\Controller' => 'Json',
        ),
        'accept_whitelist' => array(
            'INASIService\\V1\\Rpc\\Peserta\\Controller' => array(
                0 => 'application/vnd.inasi-service.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'INASIService\\V1\\Rpc\\Kunjungan\\Controller' => array(
                0 => 'application/vnd.inasi-service.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
        ),
        'content_type_whitelist' => array(
            'INASIService\\V1\\Rpc\\Peserta\\Controller' => array(
                0 => 'application/vnd.inasi-service.v1+json',
                1 => 'application/json',
            ),
            'INASIService\\V1\\Rpc\\Kunjungan\\Controller' => array(
                0 => 'application/vnd.inasi-service.v1+json',
                1 => 'application/json',
            ),
        ),
    ),
);
