<?php
return array(
    'service_manager' => array(
        'factories' => array(
            'Pasien\\V1\\Rest\\Pasien\\PasienResource' => 'Pasien\\V1\\Rest\\Pasien\\PasienResourceFactory',
        ),
    ),
    'router' => array(
        'routes' => array(
            'pasien.rest.pasien' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/pasien[/:pasien_id]',
                    'defaults' => array(
                        'controller' => 'Pasien\\V1\\Rest\\Pasien\\Controller',
                    ),
                ),
            ),
        ),
    ),
    'zf-versioning' => array(
        'uri' => array(
            0 => 'pasien.rest.pasien',
        ),
    ),
    'zf-rest' => array(
        'Pasien\\V1\\Rest\\Pasien\\Controller' => array(
            'listener' => 'Pasien\\V1\\Rest\\Pasien\\PasienResource',
            'route_name' => 'pasien.rest.pasien',
            'route_identifier_name' => 'pasien_id',
            'collection_name' => 'pasien',
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
            ),
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => 'Pasien\\V1\\Rest\\Pasien\\PasienEntity',
            'collection_class' => 'Pasien\\V1\\Rest\\Pasien\\PasienCollection',
            'service_name' => 'Pasien',
        ),
    ),
    'zf-content-negotiation' => array(
        'controllers' => array(
            'Pasien\\V1\\Rest\\Pasien\\Controller' => 'Json',
        ),
        'accept_whitelist' => array(
            'Pasien\\V1\\Rest\\Pasien\\Controller' => array(
                0 => 'application/vnd.pasien.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ),
        ),
        'content_type_whitelist' => array(
            'Pasien\\V1\\Rest\\Pasien\\Controller' => array(
                0 => 'application/vnd.pasien.v1+json',
                1 => 'application/json',
            ),
        ),
    ),
    'zf-hal' => array(
        'metadata_map' => array(
            'Pasien\\V1\\Rest\\Pasien\\PasienEntity' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'pasien.rest.pasien',
                'route_identifier_name' => 'pasien_id',
                'hydrator' => 'Zend\\Hydrator\\ArraySerializable',
            ),
            'Pasien\\V1\\Rest\\Pasien\\PasienCollection' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'pasien.rest.pasien',
                'route_identifier_name' => 'pasien_id',
                'is_collection' => true,
            ),
        ),
    ),
);
