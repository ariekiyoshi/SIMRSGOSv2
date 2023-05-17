<?php
return array(
    'router' => array(
        'routes' => array(
            'penjualan.rest.penjualan' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/penjualan/penjualan[/:nomor]',
                    'defaults' => array(
                        'controller' => 'Penjualan\\V1\\Rest\\Penjualan\\Controller',
                    ),
                ),
            ),
            'penjualan.rest.penjualan-detil' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/penjualan/penjualandetil[/:id]',
                    'defaults' => array(
                        'controller' => 'Penjualan\\V1\\Rest\\PenjualanDetil\\Controller',
                    ),
                ),
            ),
            'penjualan.rest.retur-penjualan' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/penjualan/returpenjualan[/:id]',
                    'defaults' => array(
                        'controller' => 'Penjualan\\V1\\Rest\\ReturPenjualan\\Controller',
                    ),
                ),
            ),
        ),
    ),
    'zf-versioning' => array(
        'uri' => array(
            0 => 'penjualan.rest.penjualan',
            1 => 'penjualan.rest.penjualan-detil',
            2 => 'penjualan.rest.retur-penjualan',
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'Penjualan\\V1\\Rest\\Penjualan\\PenjualanResource' => 'Penjualan\\V1\\Rest\\Penjualan\\PenjualanResourceFactory',
            'Penjualan\\V1\\Rest\\PenjualanDetil\\PenjualanDetilResource' => 'Penjualan\\V1\\Rest\\PenjualanDetil\\PenjualanDetilResourceFactory',
            'Penjualan\\V1\\Rest\\ReturPenjualan\\ReturPenjualanResource' => 'Penjualan\\V1\\Rest\\ReturPenjualan\\ReturPenjualanResourceFactory',
        ),
    ),
    'zf-rest' => array(
        'Penjualan\\V1\\Rest\\Penjualan\\Controller' => array(
            'listener' => 'Penjualan\\V1\\Rest\\Penjualan\\PenjualanResource',
            'route_name' => 'penjualan.rest.penjualan',
            'route_identifier_name' => 'nomor',
            'collection_name' => 'penjualan',
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
                0 => 'NOMOR',
                1 => 'PENGUNJUNG',
                2 => 'KETERANGAN',
                3 => 'STATUS',
                4 => 'start',
                5 => 'limit',
            ),
            'page_size' => '25',
            'page_size_param' => null,
            'entity_class' => 'Penjualan\\V1\\Rest\\Penjualan\\PenjualanEntity',
            'collection_class' => 'Penjualan\\V1\\Rest\\Penjualan\\PenjualanCollection',
            'service_name' => 'Penjualan',
        ),
        'Penjualan\\V1\\Rest\\PenjualanDetil\\Controller' => array(
            'listener' => 'Penjualan\\V1\\Rest\\PenjualanDetil\\PenjualanDetilResource',
            'route_name' => 'penjualan.rest.penjualan-detil',
            'route_identifier_name' => 'id',
            'collection_name' => 'penjualan_detil',
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
                0 => 'PENJUALAN_ID',
                1 => 'BARANG',
                2 => 'STATUS',
            ),
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => 'Penjualan\\V1\\Rest\\PenjualanDetil\\PenjualanDetilEntity',
            'collection_class' => 'Penjualan\\V1\\Rest\\PenjualanDetil\\PenjualanDetilCollection',
            'service_name' => 'PenjualanDetil',
        ),
        'Penjualan\\V1\\Rest\\ReturPenjualan\\Controller' => array(
            'listener' => 'Penjualan\\V1\\Rest\\ReturPenjualan\\ReturPenjualanResource',
            'route_name' => 'penjualan.rest.retur-penjualan',
            'route_identifier_name' => 'id',
            'collection_name' => 'retur_penjualan',
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
                0 => 'OLEH',
                1 => 'PENJUALAN_ID',
                2 => 'BARANG',
                3 => 'PENJUALAN_DETIL_ID',
            ),
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => 'Penjualan\\V1\\Rest\\ReturPenjualan\\ReturPenjualanEntity',
            'collection_class' => 'Penjualan\\V1\\Rest\\ReturPenjualan\\ReturPenjualanCollection',
            'service_name' => 'ReturPenjualan',
        ),
    ),
    'zf-content-negotiation' => array(
        'controllers' => array(
            'Penjualan\\V1\\Rest\\Penjualan\\Controller' => 'Json',
            'Penjualan\\V1\\Rest\\PenjualanDetil\\Controller' => 'Json',
            'Penjualan\\V1\\Rest\\ReturPenjualan\\Controller' => 'Json',
        ),
        'accept_whitelist' => array(
            'Penjualan\\V1\\Rest\\Penjualan\\Controller' => array(
                0 => 'application/vnd.penjualan.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ),
            'Penjualan\\V1\\Rest\\PenjualanDetil\\Controller' => array(
                0 => 'application/vnd.penjualan.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ),
            'Penjualan\\V1\\Rest\\ReturPenjualan\\Controller' => array(
                0 => 'application/vnd.penjualan.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ),
        ),
        'content_type_whitelist' => array(
            'Penjualan\\V1\\Rest\\Penjualan\\Controller' => array(
                0 => 'application/vnd.penjualan.v1+json',
                1 => 'application/json',
            ),
            'Penjualan\\V1\\Rest\\PenjualanDetil\\Controller' => array(
                0 => 'application/vnd.penjualan.v1+json',
                1 => 'application/json',
            ),
            'Penjualan\\V1\\Rest\\ReturPenjualan\\Controller' => array(
                0 => 'application/vnd.penjualan.v1+json',
                1 => 'application/json',
            ),
        ),
    ),
    'zf-hal' => array(
        'metadata_map' => array(
            'Penjualan\\V1\\Rest\\Penjualan\\PenjualanEntity' => array(
                'entity_identifier_name' => 'NOMOR',
                'route_name' => 'penjualan.rest.penjualan',
                'route_identifier_name' => 'nomor',
                'hydrator' => 'Zend\\Stdlib\\Hydrator\\ArraySerializable',
            ),
            'Penjualan\\V1\\Rest\\Penjualan\\PenjualanCollection' => array(
                'entity_identifier_name' => 'NOMOR',
                'route_name' => 'penjualan.rest.penjualan',
                'route_identifier_name' => 'nomor',
                'is_collection' => true,
            ),
            'Penjualan\\V1\\Rest\\PenjualanDetil\\PenjualanDetilEntity' => array(
                'entity_identifier_name' => 'PENJUALAN_ID',
                'route_name' => 'penjualan.rest.penjualan-detil',
                'route_identifier_name' => 'id',
                'hydrator' => 'Zend\\Stdlib\\Hydrator\\ArraySerializable',
            ),
            'Penjualan\\V1\\Rest\\PenjualanDetil\\PenjualanDetilCollection' => array(
                'entity_identifier_name' => 'PENJUALAN_ID',
                'route_name' => 'penjualan.rest.penjualan-detil',
                'route_identifier_name' => 'id',
                'is_collection' => true,
            ),
            'Penjualan\\V1\\Rest\\ReturPenjualan\\ReturPenjualanEntity' => array(
                'entity_identifier_name' => 'ID',
                'route_name' => 'penjualan.rest.retur-penjualan',
                'route_identifier_name' => 'id',
                'hydrator' => 'Zend\\Stdlib\\Hydrator\\ArraySerializable',
            ),
            'Penjualan\\V1\\Rest\\ReturPenjualan\\ReturPenjualanCollection' => array(
                'entity_identifier_name' => 'ID',
                'route_name' => 'penjualan.rest.retur-penjualan',
                'route_identifier_name' => 'id',
                'is_collection' => true,
            ),
        ),
    ),
);
