<?php
return [
    'controllers' => [
        'factories' => [
            'Aplikasi\\V1\\Rpc\\Authentication\\Controller' => \Aplikasi\V1\Rpc\Authentication\AuthenticationControllerFactory::class,
        ],
    ],
    'router' => [
        'routes' => [
            'aplikasi.rpc.authentication' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/authentication[/:action][/:id]',
                    'defaults' => [
                        'controller' => 'Aplikasi\\V1\\Rpc\\Authentication\\Controller',
                        'action' => 'authentication',
                    ],
                ],
            ],
            'aplikasi.rest.modules' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/aplikasi/modules[/:id]',
                    'defaults' => [
                        'controller' => 'Aplikasi\\V1\\Rest\\Modules\\Controller',
                    ],
                ],
            ],
            'aplikasi.rest.pengguna' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/aplikasi/pengguna[/:id]',
                    'defaults' => [
                        'controller' => 'Aplikasi\\V1\\Rest\\Pengguna\\Controller',
                    ],
                ],
            ],
            'aplikasi.rest.group-pengguna-akses-module' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/aplikasi/grouppenggunaaksesmodule[/:id]',
                    'defaults' => [
                        'controller' => 'Aplikasi\\V1\\Rest\\GroupPenggunaAksesModule\\Controller',
                    ],
                ],
            ],
            'aplikasi.rest.pengguna-akses-ruangan' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/aplikasi/penggunaaksesruangan[/:id]',
                    'defaults' => [
                        'controller' => 'Aplikasi\\V1\\Rest\\PenggunaAksesRuangan\\Controller',
                    ],
                ],
            ],
            'aplikasi.rest.pengguna-akses' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/aplikasi/penggunaakses[/:id]',
                    'defaults' => [
                        'controller' => 'Aplikasi\\V1\\Rest\\PenggunaAkses\\Controller',
                    ],
                ],
            ],
            'aplikasi.rest.instansi' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/aplikasi/instansi[/:ppk]',
                    'defaults' => [
                        'controller' => 'Aplikasi\\V1\\Rest\\Instansi\\Controller',
                    ],
                ],
            ],
            'aplikasi.rest.info-teks' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/aplikasi/infoteks[/:id]',
                    'defaults' => [
                        'controller' => 'Aplikasi\\V1\\Rest\\InfoTeks\\Controller',
                    ],
                ],
            ],
            'aplikasi.rest.sinkronisasi' => [
                'type' => 'Segment',
                'options' => [
                    'route' => 'aplikasi/sinkronisasi[/:id]',
                    'defaults' => [
                        'controller' => 'Aplikasi\\V1\\Rest\\Sinkronisasi\\Controller',
                    ],
                ],
            ],
            'aplikasi.rest.pengguna-log' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/aplikasi/penggunalog[/:pengguna_log_id]',
                    'defaults' => [
                        'controller' => 'Aplikasi\\V1\\Rest\\PenggunaLog\\Controller',
                    ],
                ],
            ],
            'aplikasi.rest.teams' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/aplikasi/teams[/:teams_id]',
                    'defaults' => [
                        'controller' => 'Aplikasi\\V1\\Rest\\Teams\\Controller',
                    ],
                ],
            ],
            'aplikasi.rest.pengguna-akses-log' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/aplikasi/pengguna/akses/log[/:pengguna_akses_log_id]',
                    'defaults' => [
                        'controller' => 'Aplikasi\\V1\\Rest\\PenggunaAksesLog\\Controller',
                    ],
                ],
            ],
            'aplikasi.rest.objek' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/aplikasi/objek[/:objek_id]',
                    'defaults' => [
                        'controller' => 'Aplikasi\\V1\\Rest\\Objek\\Controller',
                    ],
                ],
            ],
        ],
    ],
    'zf-versioning' => [
        'uri' => [
            0 => 'aplikasi.rpc.authentication',
            1 => 'aplikasi.rest.modules',
            2 => 'aplikasi.rest.pengguna',
            3 => 'aplikasi.rest.group-pengguna-akses-module',
            4 => 'aplikasi.rest.pengguna-akses-ruangan',
            5 => 'aplikasi.rest.pengguna-akses',
            6 => 'aplikasi.rest.instansi',
            7 => 'aplikasi.rest.info-teks',
            8 => 'aplikasi.rest.sinkronisasi',
            9 => 'aplikasi.rest.pengguna-log',
            10 => 'aplikasi.rest.teams',
            11 => 'aplikasi.rest.pengguna-akses-log',
            12 => 'aplikasi.rest.objek',
        ],
    ],
    'zf-rpc' => [
        'Aplikasi\\V1\\Rpc\\Authentication\\Controller' => [
            'service_name' => 'Authentication',
            'http_methods' => [
                0 => 'GET',
                1 => 'POST',
            ],
            'route_name' => 'aplikasi.rpc.authentication',
        ],
    ],
    'zf-content-negotiation' => [
        'controllers' => [
            'Aplikasi\\V1\\Rpc\\Authentication\\Controller' => 'Json',
            'Aplikasi\\V1\\Rest\\Modules\\Controller' => 'Json',
            'Aplikasi\\V1\\Rest\\Pengguna\\Controller' => 'Json',
            'Aplikasi\\V1\\Rest\\GroupPenggunaAksesModule\\Controller' => 'Json',
            'Aplikasi\\V1\\Rest\\PenggunaAksesRuangan\\Controller' => 'Json',
            'Aplikasi\\V1\\Rest\\PenggunaAkses\\Controller' => 'Json',
            'Aplikasi\\V1\\Rest\\Instansi\\Controller' => 'Json',
            'Aplikasi\\V1\\Rest\\InfoTeks\\Controller' => 'Json',
            'Aplikasi\\V1\\Rest\\Sinkronisasi\\Controller' => 'Json',
            'Aplikasi\\V1\\Rest\\PenggunaLog\\Controller' => 'Json',
            'Aplikasi\\V1\\Rest\\Teams\\Controller' => 'Json',
            'Aplikasi\\V1\\Rest\\PenggunaAksesLog\\Controller' => 'Json',
            'Aplikasi\\V1\\Rest\\Objek\\Controller' => 'Json',
        ],
        'accept_whitelist' => [
            'Aplikasi\\V1\\Rpc\\Authentication\\Controller' => [
                0 => 'application/vnd.aplikasi.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ],
            'Aplikasi\\V1\\Rest\\Modules\\Controller' => [
                0 => 'application/vnd.aplikasi.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
            'Aplikasi\\V1\\Rest\\Pengguna\\Controller' => [
                0 => 'application/vnd.aplikasi.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
            'Aplikasi\\V1\\Rest\\GroupPenggunaAksesModule\\Controller' => [
                0 => 'application/vnd.aplikasi.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
            'Aplikasi\\V1\\Rest\\PenggunaAksesRuangan\\Controller' => [
                0 => 'application/vnd.aplikasi.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
            'Aplikasi\\V1\\Rest\\PenggunaAkses\\Controller' => [
                0 => 'application/vnd.aplikasi.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
            'Aplikasi\\V1\\Rest\\Instansi\\Controller' => [
                0 => 'application/vnd.aplikasi.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
            'Aplikasi\\V1\\Rest\\InfoTeks\\Controller' => [
                0 => 'application/vnd.aplikasi.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
            'Aplikasi\\V1\\Rest\\Sinkronisasi\\Controller' => [
                0 => 'application/vnd.aplikasi.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
            'Aplikasi\\V1\\Rest\\PenggunaLog\\Controller' => [
                0 => 'application/vnd.aplikasi.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
            'Aplikasi\\V1\\Rest\\Teams\\Controller' => [
                0 => 'application/vnd.aplikasi.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
            'Aplikasi\\V1\\Rest\\PenggunaAksesLog\\Controller' => [
                0 => 'application/vnd.aplikasi.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
            'Aplikasi\\V1\\Rest\\Objek\\Controller' => [
                0 => 'application/vnd.aplikasi.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
        ],
        'content_type_whitelist' => [
            'Aplikasi\\V1\\Rpc\\Authentication\\Controller' => [
                0 => 'application/vnd.aplikasi.v1+json',
                1 => 'application/json',
            ],
            'Aplikasi\\V1\\Rest\\Modules\\Controller' => [
                0 => 'application/vnd.aplikasi.v1+json',
                1 => 'application/json',
            ],
            'Aplikasi\\V1\\Rest\\Pengguna\\Controller' => [
                0 => 'application/vnd.aplikasi.v1+json',
                1 => 'application/json',
            ],
            'Aplikasi\\V1\\Rest\\GroupPenggunaAksesModule\\Controller' => [
                0 => 'application/vnd.aplikasi.v1+json',
                1 => 'application/json',
            ],
            'Aplikasi\\V1\\Rest\\PenggunaAksesRuangan\\Controller' => [
                0 => 'application/vnd.aplikasi.v1+json',
                1 => 'application/json',
            ],
            'Aplikasi\\V1\\Rest\\PenggunaAkses\\Controller' => [
                0 => 'application/vnd.aplikasi.v1+json',
                1 => 'application/json',
            ],
            'Aplikasi\\V1\\Rest\\Instansi\\Controller' => [
                0 => 'application/vnd.aplikasi.v1+json',
                1 => 'application/json',
            ],
            'Aplikasi\\V1\\Rest\\InfoTeks\\Controller' => [
                0 => 'application/vnd.aplikasi.v1+json',
                1 => 'application/json',
            ],
            'Aplikasi\\V1\\Rest\\Sinkronisasi\\Controller' => [
                0 => 'application/vnd.aplikasi.v1+json',
                1 => 'application/json',
            ],
            'Aplikasi\\V1\\Rest\\PenggunaLog\\Controller' => [
                0 => 'application/vnd.aplikasi.v1+json',
                1 => 'application/json',
            ],
            'Aplikasi\\V1\\Rest\\Teams\\Controller' => [
                0 => 'application/vnd.aplikasi.v1+json',
                1 => 'application/json',
            ],
            'Aplikasi\\V1\\Rest\\PenggunaAksesLog\\Controller' => [
                0 => 'application/vnd.aplikasi.v1+json',
                1 => 'application/json',
            ],
            'Aplikasi\\V1\\Rest\\Objek\\Controller' => [
                0 => 'application/vnd.aplikasi.v1+json',
                1 => 'application/json',
            ],
        ],
    ],
    'service_manager' => [
        'factories' => [
            \Aplikasi\V1\Rest\Modules\ModulesResource::class => \Aplikasi\V1\Rest\Modules\ModulesResourceFactory::class,
            \Aplikasi\V1\Rest\Pengguna\PenggunaResource::class => \Aplikasi\V1\Rest\Pengguna\PenggunaResourceFactory::class,
            \Aplikasi\V1\Rest\GroupPenggunaAksesModule\GroupPenggunaAksesModuleResource::class => \Aplikasi\V1\Rest\GroupPenggunaAksesModule\GroupPenggunaAksesModuleResourceFactory::class,
            \Aplikasi\V1\Rest\PenggunaAksesRuangan\PenggunaAksesRuanganResource::class => \Aplikasi\V1\Rest\PenggunaAksesRuangan\PenggunaAksesRuanganResourceFactory::class,
            \Aplikasi\V1\Rest\PenggunaAkses\PenggunaAksesResource::class => \Aplikasi\V1\Rest\PenggunaAkses\PenggunaAksesResourceFactory::class,
            \Aplikasi\V1\Rest\Instansi\InstansiResource::class => \Aplikasi\V1\Rest\Instansi\InstansiResourceFactory::class,
            \Aplikasi\V1\Rest\InfoTeks\InfoTeksResource::class => \Aplikasi\V1\Rest\InfoTeks\InfoTeksResourceFactory::class,
            \Aplikasi\V1\Rest\Sinkronisasi\SinkronisasiResource::class => \Aplikasi\V1\Rest\Sinkronisasi\SinkronisasiResourceFactory::class,
            \Aplikasi\V1\Rest\PenggunaLog\PenggunaLogResource::class => \Aplikasi\V1\Rest\PenggunaLog\PenggunaLogResourceFactory::class,
            \Aplikasi\V1\Rest\Teams\TeamsResource::class => \Aplikasi\V1\Rest\Teams\TeamsResourceFactory::class,
            \Aplikasi\V1\Rest\PenggunaAksesLog\PenggunaAksesLogResource::class => \Aplikasi\V1\Rest\PenggunaAksesLog\PenggunaAksesLogResourceFactory::class,
            \Aplikasi\V1\Rest\Objek\ObjekResource::class => \Aplikasi\V1\Rest\Objek\ObjekResourceFactory::class,
        ],
    ],
    'zf-rest' => [
        'Aplikasi\\V1\\Rest\\Modules\\Controller' => [
            'listener' => \Aplikasi\V1\Rest\Modules\ModulesResource::class,
            'route_name' => 'aplikasi.rest.modules',
            'route_identifier_name' => 'id',
            'collection_name' => 'modules',
            'entity_http_methods' => [
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
            ],
            'collection_http_methods' => [
                0 => 'GET',
                1 => 'POST',
            ],
            'collection_query_whitelist' => [
                0 => 'NAMA',
                1 => 'LEVEL',
                2 => 'TRESS',
                3 => 'TREE',
                4 => 'TIPE',
                5 => 'GROUP_PENGGUNA',
                6 => 'PENGGUNA',
            ],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \Aplikasi\V1\Rest\Modules\ModulesEntity::class,
            'collection_class' => \Aplikasi\V1\Rest\Modules\ModulesCollection::class,
            'service_name' => 'Modules',
        ],
        'Aplikasi\\V1\\Rest\\Pengguna\\Controller' => [
            'listener' => \Aplikasi\V1\Rest\Pengguna\PenggunaResource::class,
            'route_name' => 'aplikasi.rest.pengguna',
            'route_identifier_name' => 'id',
            'collection_name' => 'pengguna',
            'entity_http_methods' => [
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
            ],
            'collection_http_methods' => [
                0 => 'GET',
                1 => 'POST',
                2 => 'PUT',
            ],
            'collection_query_whitelist' => [
                0 => 'ID',
                1 => 'LOGIN',
                2 => 'PASSWORD',
                3 => 'NAMA',
                4 => 'NIP',
                5 => 'start',
                6 => 'limit',
            ],
            'page_size' => '25',
            'page_size_param' => null,
            'entity_class' => \Aplikasi\V1\Rest\Pengguna\PenggunaEntity::class,
            'collection_class' => \Aplikasi\V1\Rest\Pengguna\PenggunaCollection::class,
            'service_name' => 'Pengguna',
        ],
        'Aplikasi\\V1\\Rest\\GroupPenggunaAksesModule\\Controller' => [
            'listener' => \Aplikasi\V1\Rest\GroupPenggunaAksesModule\GroupPenggunaAksesModuleResource::class,
            'route_name' => 'aplikasi.rest.group-pengguna-akses-module',
            'route_identifier_name' => 'id',
            'collection_name' => 'group_pengguna_akses_module',
            'entity_http_methods' => [
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
            ],
            'collection_http_methods' => [
                0 => 'GET',
                1 => 'POST',
            ],
            'collection_query_whitelist' => [
                0 => 'GROUP_PENGGUNA',
                1 => 'MODUL',
            ],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \Aplikasi\V1\Rest\GroupPenggunaAksesModule\GroupPenggunaAksesModuleEntity::class,
            'collection_class' => \Aplikasi\V1\Rest\GroupPenggunaAksesModule\GroupPenggunaAksesModuleCollection::class,
            'service_name' => 'GroupPenggunaAksesModule',
        ],
        'Aplikasi\\V1\\Rest\\PenggunaAksesRuangan\\Controller' => [
            'listener' => \Aplikasi\V1\Rest\PenggunaAksesRuangan\PenggunaAksesRuanganResource::class,
            'route_name' => 'aplikasi.rest.pengguna-akses-ruangan',
            'route_identifier_name' => 'id',
            'collection_name' => 'pengguna_akses_ruangan',
            'entity_http_methods' => [
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
            ],
            'collection_http_methods' => [
                0 => 'GET',
                1 => 'POST',
            ],
            'collection_query_whitelist' => [
                0 => 'TANGGAL',
                1 => 'PENGGUNA',
                2 => 'RUANGAN',
            ],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \Aplikasi\V1\Rest\PenggunaAksesRuangan\PenggunaAksesRuanganEntity::class,
            'collection_class' => \Aplikasi\V1\Rest\PenggunaAksesRuangan\PenggunaAksesRuanganCollection::class,
            'service_name' => 'PenggunaAksesRuangan',
        ],
        'Aplikasi\\V1\\Rest\\PenggunaAkses\\Controller' => [
            'listener' => \Aplikasi\V1\Rest\PenggunaAkses\PenggunaAksesResource::class,
            'route_name' => 'aplikasi.rest.pengguna-akses',
            'route_identifier_name' => 'id',
            'collection_name' => 'pengguna_akses',
            'entity_http_methods' => [
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
            ],
            'collection_http_methods' => [
                0 => 'GET',
                1 => 'POST',
            ],
            'collection_query_whitelist' => [
                0 => 'PENGGUNA',
                1 => 'GROUP_PENGGUNA_AKSES_MODUL',
            ],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \Aplikasi\V1\Rest\PenggunaAkses\PenggunaAksesEntity::class,
            'collection_class' => \Aplikasi\V1\Rest\PenggunaAkses\PenggunaAksesCollection::class,
            'service_name' => 'PenggunaAkses',
        ],
        'Aplikasi\\V1\\Rest\\Instansi\\Controller' => [
            'listener' => \Aplikasi\V1\Rest\Instansi\InstansiResource::class,
            'route_name' => 'aplikasi.rest.instansi',
            'route_identifier_name' => 'ppk',
            'collection_name' => 'instansi',
            'entity_http_methods' => [
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
            ],
            'collection_http_methods' => [
                0 => 'GET',
                1 => 'POST',
            ],
            'collection_query_whitelist' => [
                0 => 'PPK',
                1 => 'EMAIL',
                2 => 'WEBSITE',
            ],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \Aplikasi\V1\Rest\Instansi\InstansiEntity::class,
            'collection_class' => \Aplikasi\V1\Rest\Instansi\InstansiCollection::class,
            'service_name' => 'Instansi',
        ],
        'Aplikasi\\V1\\Rest\\InfoTeks\\Controller' => [
            'listener' => \Aplikasi\V1\Rest\InfoTeks\InfoTeksResource::class,
            'route_name' => 'aplikasi.rest.info-teks',
            'route_identifier_name' => 'id',
            'collection_name' => 'info_teks',
            'entity_http_methods' => [
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
            ],
            'collection_http_methods' => [
                0 => 'GET',
                1 => 'POST',
            ],
            'collection_query_whitelist' => [
                0 => 'TEKS',
            ],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \Aplikasi\V1\Rest\InfoTeks\InfoTeksEntity::class,
            'collection_class' => \Aplikasi\V1\Rest\InfoTeks\InfoTeksCollection::class,
            'service_name' => 'InfoTeks',
        ],
        'Aplikasi\\V1\\Rest\\Sinkronisasi\\Controller' => [
            'listener' => \Aplikasi\V1\Rest\Sinkronisasi\SinkronisasiResource::class,
            'route_name' => 'aplikasi.rest.sinkronisasi',
            'route_identifier_name' => 'id',
            'collection_name' => 'sinkronisasi',
            'entity_http_methods' => [
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
            ],
            'collection_http_methods' => [
                0 => 'GET',
                1 => 'POST',
            ],
            'collection_query_whitelist' => [],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \Aplikasi\V1\Rest\Sinkronisasi\SinkronisasiEntity::class,
            'collection_class' => \Aplikasi\V1\Rest\Sinkronisasi\SinkronisasiCollection::class,
            'service_name' => 'Sinkronisasi',
        ],
        'Aplikasi\\V1\\Rest\\PenggunaLog\\Controller' => [
            'listener' => \Aplikasi\V1\Rest\PenggunaLog\PenggunaLogResource::class,
            'route_name' => 'aplikasi.rest.pengguna-log',
            'route_identifier_name' => 'pengguna_log_id',
            'collection_name' => 'pengguna_log',
            'entity_http_methods' => [
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
            ],
            'collection_http_methods' => [
                0 => 'GET',
                1 => 'POST',
            ],
            'collection_query_whitelist' => [],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \Aplikasi\V1\Rest\PenggunaLog\PenggunaLogEntity::class,
            'collection_class' => \Aplikasi\V1\Rest\PenggunaLog\PenggunaLogCollection::class,
            'service_name' => 'PenggunaLog',
        ],
        'Aplikasi\\V1\\Rest\\Teams\\Controller' => [
            'listener' => \Aplikasi\V1\Rest\Teams\TeamsResource::class,
            'route_name' => 'aplikasi.rest.teams',
            'route_identifier_name' => 'teams_id',
            'collection_name' => 'teams',
            'entity_http_methods' => [
                0 => 'GET',
                1 => 'PUT',
            ],
            'collection_http_methods' => [
                0 => 'GET',
            ],
            'collection_query_whitelist' => [],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \Aplikasi\V1\Rest\Teams\TeamsEntity::class,
            'collection_class' => \Aplikasi\V1\Rest\Teams\TeamsCollection::class,
            'service_name' => 'Teams',
        ],
        'Aplikasi\\V1\\Rest\\PenggunaAksesLog\\Controller' => [
            'listener' => \Aplikasi\V1\Rest\PenggunaAksesLog\PenggunaAksesLogResource::class,
            'route_name' => 'aplikasi.rest.pengguna-akses-log',
            'route_identifier_name' => 'pengguna_akses_log_id',
            'collection_name' => 'pengguna_akses_log',
            'entity_http_methods' => [
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
            ],
            'collection_http_methods' => [
                0 => 'GET',
                1 => 'POST',
            ],
            'collection_query_whitelist' => [
                0 => 'PENGGUNA',
                1 => 'AKSES',
                2 => 'OBJEK',
                3 => 'TANGGAL',
                4 => 'REF',
            ],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \Aplikasi\V1\Rest\PenggunaAksesLog\PenggunaAksesLogEntity::class,
            'collection_class' => \Aplikasi\V1\Rest\PenggunaAksesLog\PenggunaAksesLogCollection::class,
            'service_name' => 'PenggunaAksesLog',
        ],
        'Aplikasi\\V1\\Rest\\Objek\\Controller' => [
            'listener' => \Aplikasi\V1\Rest\Objek\ObjekResource::class,
            'route_name' => 'aplikasi.rest.objek',
            'route_identifier_name' => 'objek_id',
            'collection_name' => 'objek',
            'entity_http_methods' => [
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
            ],
            'collection_http_methods' => [
                0 => 'GET',
                1 => 'POST',
            ],
            'collection_query_whitelist' => [],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \Aplikasi\V1\Rest\Objek\ObjekEntity::class,
            'collection_class' => \Aplikasi\V1\Rest\Objek\ObjekCollection::class,
            'service_name' => 'Objek',
        ],
    ],
    'zf-hal' => [
        'metadata_map' => [
            \Aplikasi\V1\Rest\Modules\ModulesEntity::class => [
                'entity_identifier_name' => 'ID',
                'route_name' => 'aplikasi.rest.modules',
                'route_identifier_name' => 'id',
                'hydrator' => 'Zend\\Stdlib\\Hydrator\\ArraySerializable',
            ],
            \Aplikasi\V1\Rest\Modules\ModulesCollection::class => [
                'entity_identifier_name' => 'ID',
                'route_name' => 'aplikasi.rest.modules',
                'route_identifier_name' => 'id',
                'is_collection' => true,
            ],
            \Aplikasi\V1\Rest\Pengguna\PenggunaEntity::class => [
                'entity_identifier_name' => 'ID',
                'route_name' => 'aplikasi.rest.pengguna',
                'route_identifier_name' => 'id',
                'hydrator' => 'Zend\\Stdlib\\Hydrator\\ArraySerializable',
            ],
            \Aplikasi\V1\Rest\Pengguna\PenggunaCollection::class => [
                'entity_identifier_name' => 'ID',
                'route_name' => 'aplikasi.rest.pengguna',
                'route_identifier_name' => 'id',
                'is_collection' => true,
            ],
            \Aplikasi\V1\Rest\GroupPenggunaAksesModule\GroupPenggunaAksesModuleEntity::class => [
                'entity_identifier_name' => 'ID',
                'route_name' => 'aplikasi.rest.group-pengguna-akses-module',
                'route_identifier_name' => 'id',
                'hydrator' => 'Zend\\Stdlib\\Hydrator\\ArraySerializable',
            ],
            \Aplikasi\V1\Rest\GroupPenggunaAksesModule\GroupPenggunaAksesModuleCollection::class => [
                'entity_identifier_name' => 'ID',
                'route_name' => 'aplikasi.rest.group-pengguna-akses-module',
                'route_identifier_name' => 'id',
                'is_collection' => true,
            ],
            \Aplikasi\V1\Rest\PenggunaAksesRuangan\PenggunaAksesRuanganEntity::class => [
                'entity_identifier_name' => 'ID',
                'route_name' => 'aplikasi.rest.pengguna-akses-ruangan',
                'route_identifier_name' => 'id',
                'hydrator' => 'Zend\\Stdlib\\Hydrator\\ArraySerializable',
            ],
            \Aplikasi\V1\Rest\PenggunaAksesRuangan\PenggunaAksesRuanganCollection::class => [
                'entity_identifier_name' => 'ID',
                'route_name' => 'aplikasi.rest.pengguna-akses-ruangan',
                'route_identifier_name' => 'id',
                'is_collection' => true,
            ],
            \Aplikasi\V1\Rest\PenggunaAkses\PenggunaAksesEntity::class => [
                'entity_identifier_name' => 'ID',
                'route_name' => 'aplikasi.rest.pengguna-akses',
                'route_identifier_name' => 'id',
                'hydrator' => 'Zend\\Stdlib\\Hydrator\\ArraySerializable',
            ],
            \Aplikasi\V1\Rest\PenggunaAkses\PenggunaAksesCollection::class => [
                'entity_identifier_name' => 'ID',
                'route_name' => 'aplikasi.rest.pengguna-akses',
                'route_identifier_name' => 'id',
                'is_collection' => true,
            ],
            \Aplikasi\V1\Rest\Instansi\InstansiEntity::class => [
                'entity_identifier_name' => 'PPK',
                'route_name' => 'aplikasi.rest.instansi',
                'route_identifier_name' => 'ppk',
                'hydrator' => 'Zend\\Stdlib\\Hydrator\\ArraySerializable',
            ],
            \Aplikasi\V1\Rest\Instansi\InstansiCollection::class => [
                'entity_identifier_name' => 'PPK',
                'route_name' => 'aplikasi.rest.instansi',
                'route_identifier_name' => 'ppk',
                'is_collection' => true,
            ],
            \Aplikasi\V1\Rest\InfoTeks\InfoTeksEntity::class => [
                'entity_identifier_name' => 'ID',
                'route_name' => 'aplikasi.rest.info-teks',
                'route_identifier_name' => 'id',
                'hydrator' => 'Zend\\Stdlib\\Hydrator\\ArraySerializable',
            ],
            \Aplikasi\V1\Rest\InfoTeks\InfoTeksCollection::class => [
                'entity_identifier_name' => 'ID',
                'route_name' => 'aplikasi.rest.info-teks',
                'route_identifier_name' => 'id',
                'is_collection' => true,
            ],
            \Aplikasi\V1\Rest\Sinkronisasi\SinkronisasiEntity::class => [
                'entity_identifier_name' => 'ID',
                'route_name' => 'aplikasi.rest.sinkronisasi',
                'route_identifier_name' => 'id',
                'hydrator' => \Zend\Hydrator\ArraySerializable::class,
            ],
            \Aplikasi\V1\Rest\Sinkronisasi\SinkronisasiCollection::class => [
                'entity_identifier_name' => 'ID',
                'route_name' => 'aplikasi.rest.sinkronisasi',
                'route_identifier_name' => 'id',
                'is_collection' => true,
            ],
            \Aplikasi\V1\Rest\PenggunaLog\PenggunaLogEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'aplikasi.rest.pengguna-log',
                'route_identifier_name' => 'pengguna_log_id',
                'hydrator' => \Zend\Hydrator\ArraySerializable::class,
            ],
            \Aplikasi\V1\Rest\PenggunaLog\PenggunaLogCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'aplikasi.rest.pengguna-log',
                'route_identifier_name' => 'pengguna_log_id',
                'is_collection' => true,
            ],
            \Aplikasi\V1\Rest\Teams\TeamsEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'aplikasi.rest.teams',
                'route_identifier_name' => 'teams_id',
                'hydrator' => \Zend\Hydrator\ArraySerializable::class,
            ],
            \Aplikasi\V1\Rest\Teams\TeamsCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'aplikasi.rest.teams',
                'route_identifier_name' => 'teams_id',
                'is_collection' => true,
            ],
            \Aplikasi\V1\Rest\PenggunaAksesLog\PenggunaAksesLogEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'aplikasi.rest.pengguna-akses-log',
                'route_identifier_name' => 'pengguna_akses_log_id',
                'hydrator' => \Zend\Hydrator\ArraySerializable::class,
            ],
            \Aplikasi\V1\Rest\PenggunaAksesLog\PenggunaAksesLogCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'aplikasi.rest.pengguna-akses-log',
                'route_identifier_name' => 'pengguna_akses_log_id',
                'is_collection' => true,
            ],
            \Aplikasi\V1\Rest\Objek\ObjekEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'aplikasi.rest.objek',
                'route_identifier_name' => 'objek_id',
                'hydrator' => \Zend\Hydrator\ArraySerializable::class,
            ],
            \Aplikasi\V1\Rest\Objek\ObjekCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'aplikasi.rest.objek',
                'route_identifier_name' => 'objek_id',
                'is_collection' => true,
            ],
        ],
    ],
];
