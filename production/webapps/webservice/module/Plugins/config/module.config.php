<?php
return [
    'router' => [
        'routes' => [
            'plugins.rest.request-report' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/plugins/request-report[/:request_report_id]',
                    'defaults' => [
                        'controller' => 'Plugins\\V1\\Rest\\RequestReport\\Controller',
                    ],
                ],
            ],
            'plugins.rpc.bpjs' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/plugins/bpjs[/:action][/:id]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[a-zA-Z0-9]+',
                    ],
                    'defaults' => [
                        'controller' => 'Plugins\\V1\\Rpc\\Bpjs\\Controller',
                    ],
                ],
            ],
            'plugins.rpc.inasis' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/plugins/inasis[/:action][/:id]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[a-zA-Z0-9]+',
                    ],
                    'defaults' => [
                        'controller' => 'Plugins\\V1\\Rpc\\Inasis\\Controller',
                    ],
                ],
            ],
            'plugins.rpc.inacbg' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/plugins/inacbg[/:action][/:id]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[a-zA-Z0-9]+',
                    ],
                    'defaults' => [
                        'controller' => 'Plugins\\V1\\Rpc\\Inacbg\\Controller',
                    ],
                ],
            ],
            'plugins.rpc.kemkes' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/plugins/kemkes[/:action][/:id]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[a-zA-Z0-9]+',
                    ],
                    'defaults' => [
                        'controller' => 'Plugins\\V2\\Rpc\\Kemkes\\Controller',
                    ],
                ],
            ],
            'plugins.rpc.dukcapil' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/plugins/dukcapil[/:action][/:id]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[a-zA-Z0-9]+',
                    ],
                    'defaults' => [
                        'controller' => 'Plugins\\V2\\Rpc\\Dukcapil\\Controller',
                    ],
                ],
            ],
            'plugins.rpc.pusdatin' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/plugins/pusdatin[/:action][/:id]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[a-zA-Z0-9]+',
                    ],
                    'defaults' => [
                        'controller' => 'Plugins\\V2\\Rpc\\Pusdatin\\Controller',
                    ],
                ],
            ],
            'plugins.rpc.tts' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/plugins/tts[/:action][/:id]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[a-zA-Z0-9]+',
                    ],
                    'defaults' => [
                        'controller' => 'Plugins\\V2\\Rpc\\TTS\\Controller',
                    ],
                ],
            ],
        ],
    ],
    'zf-versioning' => [
        'uri' => [
            0 => 'plugins.rest.request-report',
            1 => 'plugins.rpc.bpjs',
            2 => 'plugins.rpc.inasis',
            3 => 'plugins.rpc.inacbg',
            4 => 'plugins.rpc.kemkes',
            5 => 'plugins.rpc.dukcapil',
            6 => 'plugins.rpc.pusdatin',
            7 => 'plugins.rpc.tts',
        ],
        'default_version' => 2,
        'module_default_version' => [
            'plugins' => 2,
        ],
    ],
    'service_manager' => [
        'factories' => [
            \Plugins\V1\Rest\RequestReport\RequestReportResource::class => \Plugins\V1\Rest\RequestReport\RequestReportResourceFactory::class,
            \Plugins\V2\Rest\RequestReport\RequestReportResource::class => \Plugins\V2\Rest\RequestReport\RequestReportResourceFactory::class,
        ],
    ],
    'zf-rest' => [
        'Plugins\\V1\\Rest\\RequestReport\\Controller' => [
            'listener' => \Plugins\V1\Rest\RequestReport\RequestReportResource::class,
            'route_name' => 'plugins.rest.request-report',
            'route_identifier_name' => 'request_report_id',
            'collection_name' => 'request_report',
            'entity_http_methods' => [],
            'collection_http_methods' => [
                0 => 'POST',
            ],
            'collection_query_whitelist' => [],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \Plugins\V1\Rest\RequestReport\RequestReportEntity::class,
            'collection_class' => \Plugins\V1\Rest\RequestReport\RequestReportCollection::class,
            'service_name' => 'RequestReport',
        ],
        'Plugins\\V2\\Rest\\RequestReport\\Controller' => [
            'listener' => \Plugins\V2\Rest\RequestReport\RequestReportResource::class,
            'route_name' => 'plugins.rest.request-report',
            'route_identifier_name' => 'request_report_id',
            'collection_name' => 'request_report',
            'entity_http_methods' => [],
            'collection_http_methods' => [
                0 => 'POST',
            ],
            'collection_query_whitelist' => [],
            'page_size' => '25',
            'page_size_param' => '',
            'entity_class' => \Plugins\V2\Rest\RequestReport\RequestReportEntity::class,
            'collection_class' => \Plugins\V2\Rest\RequestReport\RequestReportCollection::class,
            'service_name' => 'RequestReport',
        ],
    ],
    'zf-content-negotiation' => [
        'controllers' => [
            'Plugins\\V1\\Rest\\RequestReport\\Controller' => 'Json',
            'Plugins\\V1\\Rpc\\Bpjs\\Controller' => 'Json',
            'Plugins\\V1\\Rpc\\Inasis\\Controller' => 'Json',
            'Plugins\\V1\\Rpc\\Inacbg\\Controller' => 'Json',
            'Plugins\\V2\\Rest\\RequestReport\\Controller' => 'Json',
            'Plugins\\V2\\Rpc\\Bpjs\\Controller' => 'Json',
            'Plugins\\V2\\Rpc\\Inasis\\Controller' => 'Json',
            'Plugins\\V2\\Rpc\\Inacbg\\Controller' => 'Json',
            'Plugins\\V2\\Rpc\\Kemkes\\Controller' => 'Json',
            'Plugins\\V2\\Rpc\\Dukcapil\\Controller' => 'Json',
            'Plugins\\V2\\Rpc\\Pusdatin\\Controller' => 'Json',
            'Plugins\\V2\\Rpc\\TTS\\Controller' => 'Json',
        ],
        'accept_whitelist' => [
            'Plugins\\V1\\Rest\\RequestReport\\Controller' => [
                0 => 'application/vnd.plugins.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
            'Plugins\\V1\\Rpc\\Bpjs\\Controller' => [
                0 => 'application/vnd.plugins.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ],
            'Plugins\\V1\\Rpc\\Inasis\\Controller' => [
                0 => 'application/vnd.plugins.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ],
            'Plugins\\V1\\Rpc\\Inacbg\\Controller' => [
                0 => 'application/vnd.plugins.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ],
            'Plugins\\V2\\Rest\\RequestReport\\Controller' => [
                0 => 'application/vnd.plugins.v2+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
            'Plugins\\V2\\Rpc\\Bpjs\\Controller' => [
                0 => 'application/vnd.plugins.v2+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ],
            'Plugins\\V2\\Rpc\\Inasis\\Controller' => [
                0 => 'application/vnd.plugins.v2+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ],
            'Plugins\\V2\\Rpc\\Inacbg\\Controller' => [
                0 => 'application/vnd.plugins.v2+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ],
            'Plugins\\V2\\Rpc\\Kemkes\\Controller' => [
                0 => 'application/vnd.plugins.v2+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ],
            'Plugins\\V2\\Rpc\\Dukcapil\\Controller' => [
                0 => 'application/vnd.plugins.v2+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ],
            'Plugins\\V2\\Rpc\\Pusdatin\\Controller' => [
                0 => 'application/vnd.plugins.v2+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ],
            'Plugins\\V2\\Rpc\\TTS\\Controller' => [
                0 => 'application/vnd.plugins.v2+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ],
        ],
        'content_type_whitelist' => [
            'Plugins\\V1\\Rest\\RequestReport\\Controller' => [
                0 => 'application/vnd.plugins.v1+json',
                1 => 'application/json',
            ],
            'Plugins\\V1\\Rpc\\Bpjs\\Controller' => [
                0 => 'application/vnd.plugins.v1+json',
                1 => 'application/json',
            ],
            'Plugins\\V1\\Rpc\\Inasis\\Controller' => [
                0 => 'application/vnd.plugins.v1+json',
                1 => 'application/json',
            ],
            'Plugins\\V1\\Rpc\\Inacbg\\Controller' => [
                0 => 'application/vnd.plugins.v1+json',
                1 => 'application/json',
            ],
            'Plugins\\V2\\Rest\\RequestReport\\Controller' => [
                0 => 'application/vnd.plugins.v2+json',
                1 => 'application/json',
            ],
            'Plugins\\V2\\Rpc\\Bpjs\\Controller' => [
                0 => 'application/vnd.plugins.v2+json',
                1 => 'application/json',
            ],
            'Plugins\\V2\\Rpc\\Inasis\\Controller' => [
                0 => 'application/vnd.plugins.v2+json',
                1 => 'application/json',
            ],
            'Plugins\\V2\\Rpc\\Inacbg\\Controller' => [
                0 => 'application/vnd.plugins.v2+json',
                1 => 'application/json',
            ],
            'Plugins\\V2\\Rpc\\Kemkes\\Controller' => [
                0 => 'application/vnd.plugins.v2+json',
                1 => 'application/json',
            ],
            'Plugins\\V2\\Rpc\\Dukcapil\\Controller' => [
                0 => 'application/vnd.plugins.v2+json',
                1 => 'application/json',
            ],
            'Plugins\\V2\\Rpc\\Pusdatin\\Controller' => [
                0 => 'application/vnd.plugins.v2+json',
                1 => 'application/json',
            ],
            'Plugins\\V2\\Rpc\\TTS\\Controller' => [
                0 => 'application/vnd.plugins.v2+json',
                1 => 'application/json',
            ],
        ],
    ],
    'zf-hal' => [
        'metadata_map' => [
            \Plugins\V1\Rest\RequestReport\RequestReportEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'plugins.rest.request-report',
                'route_identifier_name' => 'request_report_id',
                'hydrator' => 'Zend\\Stdlib\\Hydrator\\ArraySerializable',
            ],
            \Plugins\V1\Rest\RequestReport\RequestReportCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'plugins.rest.request-report',
                'route_identifier_name' => 'request_report_id',
                'is_collection' => true,
            ],
            \Plugins\V2\Rest\RequestReport\RequestReportEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'plugins.rest.request-report',
                'route_identifier_name' => 'request_report_id',
                'hydrator' => 'Zend\\Stdlib\\Hydrator\\ArraySerializable',
            ],
            \Plugins\V2\Rest\RequestReport\RequestReportCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'plugins.rest.request-report',
                'route_identifier_name' => 'request_report_id',
                'is_collection' => '1',
            ],
        ],
    ],
    'zf-content-validation' => [
        'Plugins\\V1\\Rest\\RequestReport\\Controller' => [
            'input_filter' => 'Plugins\\V1\\Rest\\RequestReport\\Validator',
        ],
        'Plugins\\V2\\Rest\\RequestReport\\Controller' => [
            'input_filter' => 'Plugins\\V2\\Rest\\RequestReport\\Validator',
        ],
    ],
    'input_filter_specs' => [
        'Plugins\\V1\\Rest\\RequestReport\\Validator' => [
            0 => [
                'name' => 'NAME',
                'required' => true,
                'filters' => [],
                'validators' => [],
                'allow_empty' => false,
                'continue_if_empty' => false,
            ],
            1 => [
                'name' => 'CONNECTION_NUMBER',
                'required' => true,
                'filters' => [],
                'validators' => [],
                'allow_empty' => false,
                'continue_if_empty' => false,
            ],
            2 => [
                'name' => 'TYPE',
                'required' => true,
                'filters' => [],
                'validators' => [],
                'allow_empty' => false,
                'continue_if_empty' => false,
            ],
            3 => [
                'name' => 'PARAMETER',
                'required' => true,
                'filters' => [],
                'validators' => [],
                'allow_empty' => false,
                'continue_if_empty' => true,
            ],
            4 => [
                'name' => 'REQUEST_FOR_PRINT',
                'required' => true,
                'filters' => [],
                'validators' => [],
                'allow_empty' => false,
                'continue_if_empty' => true,
            ],
        ],
        'Plugins\\V2\\Rest\\RequestReport\\Validator' => [
            0 => [
                'name' => 'NAME',
                'required' => '1',
                'filters' => [],
                'validators' => [],
                'allow_empty' => '',
                'continue_if_empty' => '',
            ],
            1 => [
                'name' => 'CONNECTION_NUMBER',
                'required' => '1',
                'filters' => [],
                'validators' => [],
                'allow_empty' => '',
                'continue_if_empty' => '',
            ],
            2 => [
                'name' => 'TYPE',
                'required' => '1',
                'filters' => [],
                'validators' => [],
                'allow_empty' => '',
                'continue_if_empty' => '',
            ],
            3 => [
                'name' => 'PARAMETER',
                'required' => '1',
                'filters' => [],
                'validators' => [],
                'allow_empty' => '',
                'continue_if_empty' => '1',
            ],
            4 => [
                'name' => 'REQUEST_FOR_PRINT',
                'required' => '1',
                'filters' => [],
                'validators' => [],
                'allow_empty' => '',
                'continue_if_empty' => '1',
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            'Plugins\\V1\\Rpc\\Bpjs\\Controller' => \Plugins\V1\Rpc\Bpjs\BpjsControllerFactory::class,
            'Plugins\\V1\\Rpc\\Inasis\\Controller' => \Plugins\V1\Rpc\Inasis\InasisControllerFactory::class,
            'Plugins\\V1\\Rpc\\Inacbg\\Controller' => \Plugins\V1\Rpc\Inacbg\InacbgControllerFactory::class,
            'Plugins\\V2\\Rpc\\Bpjs\\Controller' => \Plugins\V2\Rpc\Bpjs\BpjsControllerFactory::class,
            'Plugins\\V2\\Rpc\\Inasis\\Controller' => \Plugins\V2\Rpc\Inasis\InasisControllerFactory::class,
            'Plugins\\V2\\Rpc\\Inacbg\\Controller' => \Plugins\V2\Rpc\Inacbg\InacbgControllerFactory::class,
            'Plugins\\V2\\Rpc\\Kemkes\\Controller' => \Plugins\V2\Rpc\Kemkes\KemkesControllerFactory::class,
            'Plugins\\V2\\Rpc\\Dukcapil\\Controller' => \Plugins\V2\Rpc\Dukcapil\DukcapilControllerFactory::class,
            'Plugins\\V2\\Rpc\\Pusdatin\\Controller' => \Plugins\V2\Rpc\Pusdatin\PusdatinControllerFactory::class,
            'Plugins\\V2\\Rpc\\TTS\\Controller' => \Plugins\V2\Rpc\TTS\TTSControllerFactory::class,
        ],
    ],
    'zf-rpc' => [
        'Plugins\\V1\\Rpc\\Bpjs\\Controller' => [
            'service_name' => 'bpjs',
            'http_methods' => [
                0 => 'GET',
                1 => 'POST',
            ],
            'route_name' => 'plugins.rpc.bpjs',
        ],
        'Plugins\\V1\\Rpc\\Inasis\\Controller' => [
            'service_name' => 'inasis',
            'http_methods' => [
                0 => 'GET',
                1 => 'POST',
            ],
            'route_name' => 'plugins.rpc.inasis',
        ],
        'Plugins\\V1\\Rpc\\Inacbg\\Controller' => [
            'service_name' => 'inacbg',
            'http_methods' => [
                0 => 'GET',
                1 => 'POST',
            ],
            'route_name' => 'plugins.rpc.inacbg',
        ],
        'Plugins\\V2\\Rpc\\Bpjs\\Controller' => [
            'service_name' => 'bpjs',
            'http_methods' => [
                0 => 'GET',
                1 => 'POST',
            ],
            'route_name' => 'plugins.rpc.bpjs',
        ],
        'Plugins\\V2\\Rpc\\Inasis\\Controller' => [
            'service_name' => 'inasis',
            'http_methods' => [
                0 => 'GET',
                1 => 'POST',
            ],
            'route_name' => 'plugins.rpc.inasis',
        ],
        'Plugins\\V2\\Rpc\\Inacbg\\Controller' => [
            'service_name' => 'inacbg',
            'http_methods' => [
                0 => 'GET',
                1 => 'POST',
            ],
            'route_name' => 'plugins.rpc.inacbg',
        ],
        'Plugins\\V2\\Rpc\\Kemkes\\Controller' => [
            'service_name' => 'Kemkes',
            'http_methods' => [
                0 => 'GET',
                1 => 'POST',
                2 => 'PUT',
            ],
            'route_name' => 'plugins.rpc.kemkes',
        ],
        'Plugins\\V2\\Rpc\\Dukcapil\\Controller' => [
            'service_name' => 'Dukcapil',
            'http_methods' => [
                0 => 'GET',
            ],
            'route_name' => 'plugins.rpc.dukcapil',
        ],
        'Plugins\\V2\\Rpc\\Pusdatin\\Controller' => [
            'service_name' => 'Pusdatin',
            'http_methods' => [
                0 => 'GET',
            ],
            'route_name' => 'plugins.rpc.pusdatin',
        ],
        'Plugins\\V2\\Rpc\\TTS\\Controller' => [
            'service_name' => 'TTS',
            'http_methods' => [
                0 => 'GET',
            ],
            'route_name' => 'plugins.rpc.tts',
        ],
    ],
    'zf-mvc-auth' => [
        'authorization' => [
            'Plugins\\V2\\Rpc\\Pusdatin\\Controller' => [
                'actions' => [
                    'Pusdatin' => [
                        'GET' => false,
                        'POST' => false,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ],
                ],
            ],
            'Plugins\\V2\\Rpc\\Bpjs\\Controller' => [
                'actions' => [
                    'Bpjs' => [
                        'GET' => false,
                        'POST' => true,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ],
                ],
            ],
        ],
    ],
];
