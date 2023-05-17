<?php
return array(
    'router' => array(
        'routes' => array(
            'pembatalan.rest.pembatalan-kunjungan' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/pembatalan/pembatalankunjungan[/:id]',
                    'defaults' => array(
                        'controller' => 'Pembatalan\\V1\\Rest\\PembatalanKunjungan\\Controller',
                    ),
                ),
            ),
            'pembatalan.rest.pembatalan-retur' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/pembatalan-retur[/:pembatalan_retur_id]',
                    'defaults' => array(
                        'controller' => 'Pembatalan\\V1\\Rest\\PembatalanRetur\\Controller',
                    ),
                ),
            ),
        ),
    ),
    'zf-versioning' => array(
        'uri' => array(
            0 => 'pembatalan.rest.pembatalan-kunjungan',
            1 => 'pembatalan.rest.pembatalan-retur',
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'Pembatalan\\V1\\Rest\\PembatalanKunjungan\\PembatalanKunjunganResource' => 'Pembatalan\\V1\\Rest\\PembatalanKunjungan\\PembatalanKunjunganResourceFactory',
            'Pembatalan\\V1\\Rest\\PembatalanRetur\\PembatalanReturResource' => 'Pembatalan\\V1\\Rest\\PembatalanRetur\\PembatalanReturResourceFactory',
        ),
    ),
    'zf-rest' => array(
        'Pembatalan\\V1\\Rest\\PembatalanKunjungan\\Controller' => array(
            'listener' => 'Pembatalan\\V1\\Rest\\PembatalanKunjungan\\PembatalanKunjunganResource',
            'route_name' => 'pembatalan.rest.pembatalan-kunjungan',
            'route_identifier_name' => 'id',
            'collection_name' => 'pembatalan_kunjungan',
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
                0 => 'KUNJUNGAN',
                1 => 'JENIS',
                2 => 'STATUS',
            ),
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => 'Pembatalan\\V1\\Rest\\PembatalanKunjungan\\PembatalanKunjunganEntity',
            'collection_class' => 'Pembatalan\\V1\\Rest\\PembatalanKunjungan\\PembatalanKunjunganCollection',
            'service_name' => 'PembatalanKunjungan',
        ),
        'Pembatalan\\V1\\Rest\\PembatalanRetur\\Controller' => array(
            'listener' => 'Pembatalan\\V1\\Rest\\PembatalanRetur\\PembatalanReturResource',
            'route_name' => 'pembatalan.rest.pembatalan-retur',
            'route_identifier_name' => 'pembatalan_retur_id',
            'collection_name' => 'pembatalan_retur',
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
            'collection_query_whitelist' => array(),
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => 'Pembatalan\\V1\\Rest\\PembatalanRetur\\PembatalanReturEntity',
            'collection_class' => 'Pembatalan\\V1\\Rest\\PembatalanRetur\\PembatalanReturCollection',
            'service_name' => 'PembatalanRetur',
        ),
    ),
    'zf-content-negotiation' => array(
        'controllers' => array(
            'Pembatalan\\V1\\Rest\\PembatalanKunjungan\\Controller' => 'Json',
            'Pembatalan\\V1\\Rest\\PembatalanRetur\\Controller' => 'HalJson',
        ),
        'accept_whitelist' => array(
            'Pembatalan\\V1\\Rest\\PembatalanKunjungan\\Controller' => array(
                0 => 'application/vnd.pembatalan.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ),
            'Pembatalan\\V1\\Rest\\PembatalanRetur\\Controller' => array(
                0 => 'application/vnd.pembatalan.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ),
        ),
        'content_type_whitelist' => array(
            'Pembatalan\\V1\\Rest\\PembatalanKunjungan\\Controller' => array(
                0 => 'application/vnd.pembatalan.v1+json',
                1 => 'application/json',
            ),
            'Pembatalan\\V1\\Rest\\PembatalanRetur\\Controller' => array(
                0 => 'application/vnd.pembatalan.v1+json',
                1 => 'application/json',
            ),
        ),
    ),
    'zf-hal' => array(
        'metadata_map' => array(
            'Pembatalan\\V1\\Rest\\PembatalanKunjungan\\PembatalanKunjunganEntity' => array(
                'entity_identifier_name' => 'ID',
                'route_name' => 'pembatalan.rest.pembatalan-kunjungan',
                'route_identifier_name' => 'id',
                'hydrator' => 'Zend\\Stdlib\\Hydrator\\ArraySerializable',
            ),
            'Pembatalan\\V1\\Rest\\PembatalanKunjungan\\PembatalanKunjunganCollection' => array(
                'entity_identifier_name' => 'ID',
                'route_name' => 'pembatalan.rest.pembatalan-kunjungan',
                'route_identifier_name' => 'id',
                'is_collection' => true,
            ),
            'Pembatalan\\V1\\Rest\\PembatalanRetur\\PembatalanReturEntity' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'pembatalan.rest.pembatalan-retur',
                'route_identifier_name' => 'pembatalan_retur_id',
                'hydrator' => 'Zend\\Stdlib\\Hydrator\\ArraySerializable',
            ),
            'Pembatalan\\V1\\Rest\\PembatalanRetur\\PembatalanReturCollection' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'pembatalan.rest.pembatalan-retur',
                'route_identifier_name' => 'pembatalan_retur_id',
                'is_collection' => true,
            ),
        ),
    ),
);
