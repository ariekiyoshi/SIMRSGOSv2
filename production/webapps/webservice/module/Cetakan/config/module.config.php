<?php
return array(
    'router' => array(
        'routes' => array(
            'cetakan.rest.karcis-pasien' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/cetakan/karcispasien[/:id]',
                    'defaults' => array(
                        'controller' => 'Cetakan\\V1\\Rest\\KarcisPasien\\Controller',
                    ),
                ),
            ),
            'cetakan.rest.kartu-pasien' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/cetakan/kartupasien[/:id]',
                    'defaults' => array(
                        'controller' => 'Cetakan\\V1\\Rest\\KartuPasien\\Controller',
                    ),
                ),
            ),
            'cetakan.rest.kwitansi-pembayaran' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/cetakan/kwitansipembayaran[/:tagihan]',
                    'defaults' => array(
                        'controller' => 'Cetakan\\V1\\Rest\\KwitansiPembayaran\\Controller',
                    ),
                ),
            ),
        ),
    ),
    'zf-versioning' => array(
        'uri' => array(
            0 => 'cetakan.rest.karcis-pasien',
            1 => 'cetakan.rest.kartu-pasien',
            2 => 'cetakan.rest.kwitansi-pembayaran',
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'Cetakan\\V1\\Rest\\KarcisPasien\\KarcisPasienResource' => 'Cetakan\\V1\\Rest\\KarcisPasien\\KarcisPasienResourceFactory',
            'Cetakan\\V1\\Rest\\KartuPasien\\KartuPasienResource' => 'Cetakan\\V1\\Rest\\KartuPasien\\KartuPasienResourceFactory',
            'Cetakan\\V1\\Rest\\KwitansiPembayaran\\KwitansiPembayaranResource' => 'Cetakan\\V1\\Rest\\KwitansiPembayaran\\KwitansiPembayaranResourceFactory',
        ),
    ),
    'zf-rest' => array(
        'Cetakan\\V1\\Rest\\KarcisPasien\\Controller' => array(
            'listener' => 'Cetakan\\V1\\Rest\\KarcisPasien\\KarcisPasienResource',
            'route_name' => 'cetakan.rest.karcis-pasien',
            'route_identifier_name' => 'id',
            'collection_name' => 'karcis_pasien',
            'entity_http_methods' => array(
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
            ),
            'collection_http_methods' => array(
                0 => 'GET',
                1 => 'POST',
            ),
            'collection_query_whitelist' => array(
                0 => 'NOPEN',
                1 => 'JENIS',
                2 => 'TANGGAL',
            ),
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => 'Cetakan\\V1\\Rest\\KarcisPasien\\KarcisPasienEntity',
            'collection_class' => 'Cetakan\\V1\\Rest\\KarcisPasien\\KarcisPasienCollection',
            'service_name' => 'KarcisPasien',
        ),
        'Cetakan\\V1\\Rest\\KartuPasien\\Controller' => array(
            'listener' => 'Cetakan\\V1\\Rest\\KartuPasien\\KartuPasienResource',
            'route_name' => 'cetakan.rest.kartu-pasien',
            'route_identifier_name' => 'id',
            'collection_name' => 'kartu_pasien',
            'entity_http_methods' => array(
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
            ),
            'collection_http_methods' => array(
                0 => 'GET',
                1 => 'POST',
            ),
            'collection_query_whitelist' => array(
                0 => 'NORM',
                1 => 'JENIS',
                2 => 'TANGGAL',
                3 => 'STATUS',
            ),
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => 'Cetakan\\V1\\Rest\\KartuPasien\\KartuPasienEntity',
            'collection_class' => 'Cetakan\\V1\\Rest\\KartuPasien\\KartuPasienCollection',
            'service_name' => 'KartuPasien',
        ),
        'Cetakan\\V1\\Rest\\KwitansiPembayaran\\Controller' => array(
            'listener' => 'Cetakan\\V1\\Rest\\KwitansiPembayaran\\KwitansiPembayaranResource',
            'route_name' => 'cetakan.rest.kwitansi-pembayaran',
            'route_identifier_name' => 'tagihan',
            'collection_name' => 'kwitansi_pembayaran',
            'entity_http_methods' => array(
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
            ),
            'collection_http_methods' => array(
                0 => 'GET',
                1 => 'POST',
            ),
            'collection_query_whitelist' => array(
                0 => 'TAGIHAN',
                1 => 'TANGGAL',
                2 => 'NAMA',
                3 => 'STATUS',
            ),
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => 'Cetakan\\V1\\Rest\\KwitansiPembayaran\\KwitansiPembayaranEntity',
            'collection_class' => 'Cetakan\\V1\\Rest\\KwitansiPembayaran\\KwitansiPembayaranCollection',
            'service_name' => 'KwitansiPembayaran',
        ),
    ),
    'zf-content-negotiation' => array(
        'controllers' => array(
            'Cetakan\\V1\\Rest\\KarcisPasien\\Controller' => 'Json',
            'Cetakan\\V1\\Rest\\KartuPasien\\Controller' => 'Json',
            'Cetakan\\V1\\Rest\\KwitansiPembayaran\\Controller' => 'Json',
        ),
        'accept_whitelist' => array(
            'Cetakan\\V1\\Rest\\KarcisPasien\\Controller' => array(
                0 => 'application/vnd.cetakan.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ),
            'Cetakan\\V1\\Rest\\KartuPasien\\Controller' => array(
                0 => 'application/vnd.cetakan.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ),
            'Cetakan\\V1\\Rest\\KwitansiPembayaran\\Controller' => array(
                0 => 'application/vnd.cetakan.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ),
        ),
        'content_type_whitelist' => array(
            'Cetakan\\V1\\Rest\\KarcisPasien\\Controller' => array(
                0 => 'application/vnd.cetakan.v1+json',
                1 => 'application/json',
            ),
            'Cetakan\\V1\\Rest\\KartuPasien\\Controller' => array(
                0 => 'application/vnd.cetakan.v1+json',
                1 => 'application/json',
            ),
            'Cetakan\\V1\\Rest\\KwitansiPembayaran\\Controller' => array(
                0 => 'application/vnd.cetakan.v1+json',
                1 => 'application/json',
            ),
        ),
    ),
    'zf-hal' => array(
        'metadata_map' => array(
            'Cetakan\\V1\\Rest\\KarcisPasien\\KarcisPasienEntity' => array(
                'entity_identifier_name' => 'ID',
                'route_name' => 'cetakan.rest.karcis-pasien',
                'route_identifier_name' => 'id',
                'hydrator' => 'Zend\\Stdlib\\Hydrator\\ArraySerializable',
            ),
            'Cetakan\\V1\\Rest\\KarcisPasien\\KarcisPasienCollection' => array(
                'entity_identifier_name' => 'ID',
                'route_name' => 'cetakan.rest.karcis-pasien',
                'route_identifier_name' => 'id',
                'is_collection' => true,
            ),
            'Cetakan\\V1\\Rest\\KartuPasien\\KartuPasienEntity' => array(
                'entity_identifier_name' => 'ID',
                'route_name' => 'cetakan.rest.kartu-pasien',
                'route_identifier_name' => 'id',
                'hydrator' => 'Zend\\Stdlib\\Hydrator\\ArraySerializable',
            ),
            'Cetakan\\V1\\Rest\\KartuPasien\\KartuPasienCollection' => array(
                'entity_identifier_name' => 'ID',
                'route_name' => 'cetakan.rest.kartu-pasien',
                'route_identifier_name' => 'id',
                'is_collection' => true,
            ),
            'Cetakan\\V1\\Rest\\KwitansiPembayaran\\KwitansiPembayaranEntity' => array(
                'entity_identifier_name' => 'TAGIHAN',
                'route_name' => 'cetakan.rest.kwitansi-pembayaran',
                'route_identifier_name' => 'tagihan',
                'hydrator' => 'Zend\\Stdlib\\Hydrator\\ArraySerializable',
            ),
            'Cetakan\\V1\\Rest\\KwitansiPembayaran\\KwitansiPembayaranCollection' => array(
                'entity_identifier_name' => 'TAGIHAN',
                'route_name' => 'cetakan.rest.kwitansi-pembayaran',
                'route_identifier_name' => 'tagihan',
                'is_collection' => true,
            ),
        ),
    ),
);
