<?php
return [
    'service_manager' => [
        'factories' => [
            \Pegawai\V1\Rest\KartuIdentitas\KartuIdentitasResource::class => \Pegawai\V1\Rest\KartuIdentitas\KartuIdentitasResourceFactory::class,
            \Pegawai\V1\Rest\KontakPegawai\KontakPegawaiResource::class => \Pegawai\V1\Rest\KontakPegawai\KontakPegawaiResourceFactory::class,
        ],
    ],
    'router' => [
        'routes' => [
            'pegawai.rest.kartu-identitas' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/pegawai/kartuidentitas[/:id]',
                    'defaults' => [
                        'controller' => 'Pegawai\\V1\\Rest\\KartuIdentitas\\Controller',
                    ],
                ],
            ],
            'pegawai.rest.kontak-pegawai' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/pegawai/kontakpegawai[/:id]',
                    'defaults' => [
                        'controller' => 'Pegawai\\V1\\Rest\\KontakPegawai\\Controller',
                    ],
                ],
            ],
        ],
    ],
    'zf-versioning' => [
        'uri' => [
            1 => 'pegawai.rest.kartu-identitas',
            0 => 'pegawai.rest.kontak-pegawai',
        ],
    ],
    'zf-rest' => [
        'Pegawai\\V1\\Rest\\KartuIdentitas\\Controller' => [
            'listener' => \Pegawai\V1\Rest\KartuIdentitas\KartuIdentitasResource::class,
            'route_name' => 'pegawai.rest.kartu-identitas',
            'route_identifier_name' => 'id',
            'collection_name' => 'kartu_identitas',
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
                0 => 'JENIS',
                1 => 'NIP',
                2 => 'NOMOR',
                3 => 'STATUS',
            ],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \Pegawai\V1\Rest\KartuIdentitas\KartuIdentitasEntity::class,
            'collection_class' => \Pegawai\V1\Rest\KartuIdentitas\KartuIdentitasCollection::class,
            'service_name' => 'KartuIdentitas',
        ],
        'Pegawai\\V1\\Rest\\KontakPegawai\\Controller' => [
            'listener' => \Pegawai\V1\Rest\KontakPegawai\KontakPegawaiResource::class,
            'route_name' => 'pegawai.rest.kontak-pegawai',
            'route_identifier_name' => 'id',
            'collection_name' => 'kontak_pegawai',
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
                0 => 'NIP',
            ],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \Pegawai\V1\Rest\KontakPegawai\KontakPegawaiEntity::class,
            'collection_class' => \Pegawai\V1\Rest\KontakPegawai\KontakPegawaiCollection::class,
            'service_name' => 'KontakPegawai',
        ],
    ],
    'zf-content-negotiation' => [
        'controllers' => [
            'Pegawai\\V1\\Rest\\KartuIdentitas\\Controller' => 'Json',
            'Pegawai\\V1\\Rest\\KontakPegawai\\Controller' => 'Json',
        ],
        'accept_whitelist' => [
            'Pegawai\\V1\\Rest\\KartuIdentitas\\Controller' => [
                0 => 'application/vnd.pegawai.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
            'Pegawai\\V1\\Rest\\KontakPegawai\\Controller' => [
                0 => 'application/vnd.pegawai.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
        ],
        'content_type_whitelist' => [
            'Pegawai\\V1\\Rest\\KartuIdentitas\\Controller' => [
                0 => 'application/vnd.pegawai.v1+json',
                1 => 'application/json',
            ],
            'Pegawai\\V1\\Rest\\KontakPegawai\\Controller' => [
                0 => 'application/vnd.pegawai.v1+json',
                1 => 'application/json',
            ],
        ],
    ],
    'zf-hal' => [
        'metadata_map' => [
            \Pegawai\V1\Rest\KartuIdentitas\KartuIdentitasEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'pegawai.rest.kartu-identitas',
                'route_identifier_name' => 'id',
                'hydrator' => \Zend\Hydrator\ArraySerializable::class,
            ],
            \Pegawai\V1\Rest\KartuIdentitas\KartuIdentitasCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'pegawai.rest.kartu-identitas',
                'route_identifier_name' => 'id',
                'is_collection' => true,
            ],
            \Pegawai\V1\Rest\KontakPegawai\KontakPegawaiEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'pegawai.rest.kontak-pegawai',
                'route_identifier_name' => 'id',
                'hydrator' => \Zend\Hydrator\ArraySerializable::class,
            ],
            \Pegawai\V1\Rest\KontakPegawai\KontakPegawaiCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'pegawai.rest.kontak-pegawai',
                'route_identifier_name' => 'id',
                'is_collection' => true,
            ],
        ],
    ],
];
