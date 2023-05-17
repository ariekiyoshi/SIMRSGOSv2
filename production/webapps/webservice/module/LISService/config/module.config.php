<?php
return array(
    'controllers' => array(
        'factories' => array(
            'LISService\\V1\\Rpc\\Service\\Controller' => 'LISService\\V1\\Rpc\\Service\\ServiceControllerFactory',
        ),
    ),
    'router' => array(
        'routes' => array(
            'lis-service.rpc.service' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/lis/service[/:action][/:id]',
					'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'LISService\\V1\\Rpc\\Service\\Controller',
                    ),
                ),
            ),
        ),
    ),
    'zf-versioning' => array(
        'uri' => array(
            0 => 'lis-service.rpc.service',
        ),
    ),
    'zf-rpc' => array(
        'LISService\\V1\\Rpc\\Service\\Controller' => array(
            'service_name' => 'Service',
            'http_methods' => array(
                0 => 'GET',
            ),
            'route_name' => 'lis-service.rpc.service',
        ),
    ),
    'zf-content-negotiation' => array(
        'controllers' => array(
            'LISService\\V1\\Rpc\\Service\\Controller' => 'Json',
        ),
        'accept_whitelist' => array(
            'LISService\\V1\\Rpc\\Service\\Controller' => array(
                0 => 'application/vnd.lis-service.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
        ),
        'content_type_whitelist' => array(
            'LISService\\V1\\Rpc\\Service\\Controller' => array(
                0 => 'application/vnd.lis-service.v1+json',
                1 => 'application/json',
            ),
        ),
    ),
);
