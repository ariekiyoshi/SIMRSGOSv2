<?php
return [
    'router' => [
        'routes' => [
            'informasi.rest.infromasi-ruang-kamar-tidur' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/informasi/ruangkamartidur[/:id]',
                    'defaults' => [
                        'controller' => 'Informasi\\V1\\Rest\\InfromasiRuangKamarTidur\\Controller',
                    ],
                ],
            ],
        ],
    ],
    'zf-versioning' => [
        'uri' => [
            0 => 'informasi.rest.infromasi-ruang-kamar-tidur',
        ],
    ],
    'service_manager' => [
        'factories' => [
            \Informasi\V1\Rest\InfromasiRuangKamarTidur\InfromasiRuangKamarTidurResource::class => \Informasi\V1\Rest\InfromasiRuangKamarTidur\InfromasiRuangKamarTidurResourceFactory::class,
        ],
    ],
    'zf-rest' => [
        'Informasi\\V1\\Rest\\InfromasiRuangKamarTidur\\Controller' => [
            'listener' => \Informasi\V1\Rest\InfromasiRuangKamarTidur\InfromasiRuangKamarTidurResource::class,
            'route_name' => 'informasi.rest.infromasi-ruang-kamar-tidur',
            'route_identifier_name' => 'id',
            'collection_name' => 'infromasi_ruang_kamar_tidur',
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
                0 => 'STATUS',
                1 => 'PASIEN',
                2 => 'RUANGAN',
                3 => 'KAMAR',
            ],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \Informasi\V1\Rest\InfromasiRuangKamarTidur\InfromasiRuangKamarTidurEntity::class,
            'collection_class' => \Informasi\V1\Rest\InfromasiRuangKamarTidur\InfromasiRuangKamarTidurCollection::class,
            'service_name' => 'InfromasiRuangKamarTidur',
        ],
    ],
    'zf-content-negotiation' => [
        'controllers' => [
            'Informasi\\V1\\Rest\\InfromasiRuangKamarTidur\\Controller' => 'Json',
        ],
        'accept_whitelist' => [
            'Informasi\\V1\\Rest\\InfromasiRuangKamarTidur\\Controller' => [
                0 => 'application/vnd.informasi.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
        ],
        'content_type_whitelist' => [
            'Informasi\\V1\\Rest\\InfromasiRuangKamarTidur\\Controller' => [
                0 => 'application/vnd.informasi.v1+json',
                1 => 'application/json',
            ],
        ],
    ],
    'zf-hal' => [
        'metadata_map' => [
            \Informasi\V1\Rest\InfromasiRuangKamarTidur\InfromasiRuangKamarTidurEntity::class => [
                'entity_identifier_name' => 'ID',
                'route_name' => 'informasi.rest.infromasi-ruang-kamar-tidur',
                'route_identifier_name' => 'id',
                'hydrator' => 'Zend\\Stdlib\\Hydrator\\ArraySerializable',
            ],
            \Informasi\V1\Rest\InfromasiRuangKamarTidur\InfromasiRuangKamarTidurCollection::class => [
                'entity_identifier_name' => 'ID',
                'route_name' => 'informasi.rest.infromasi-ruang-kamar-tidur',
                'route_identifier_name' => 'id',
                'is_collection' => true,
            ],
        ],
    ],
];
