<?php
return array(
    'controllers' => array(
        'factories' => array(
            'INACBGService\\V1\\Rpc\\Grouper\\Controller' => 'INACBGService\\V1\\Rpc\\Grouper\\GrouperControllerFactory',
        ),
    ),
    'router' => array(
        'routes' => array(
            'inacbg-service.rpc.grouper' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/inacbgservice/grouper[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[a-zA-Z0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'INACBGService\\V1\\Rpc\\Grouper\\Controller',
                    ),
                ),
            ),
            'inacbg-service.rest.map' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/inacbgservice/map[/:id]',
                    'defaults' => array(
                        'controller' => 'INACBGService\\V1\\Rest\\Map\\Controller',
                    ),
                ),
            ),
            'inacbg-service.rest.inacbg' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/inacbgservice/inacbg[/:id]',
                    'defaults' => array(
                        'controller' => 'INACBGService\\V1\\Rest\\Inacbg\\Controller',
                    ),
                ),
            ),
            'inacbg-service.rest.list-procedure' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/inacbgservice/listprocedure[/:id]',
                    'defaults' => array(
                        'controller' => 'INACBGService\\V1\\Rest\\ListProcedure\\Controller',
                    ),
                ),
            ),
            'inacbg-service.rest.grouping' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/inacbgservice/grouping[/:id]',
                    'defaults' => array(
                        'controller' => 'INACBGService\\V1\\Rest\\Grouping\\Controller',
                    ),
                ),
            ),
            'inacbg-service.rest.hasil-grouping' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/inacbgservice/hasilgrouping[/:id]',
                    'defaults' => array(
                        'controller' => 'INACBGService\\V1\\Rest\\HasilGrouping\\Controller',
                    ),
                ),
            ),
            'inacbg-service.rest.tipe-inacbg' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/inacbgservice/tipe[/:id]',
                    'defaults' => array(
                        'controller' => 'INACBGService\\V1\\Rest\\TipeINACBG\\Controller',
                    ),
                ),
            ),
        ),
    ),
    'zf-versioning' => array(
        'uri' => array(
            0 => 'inacbg-service.rpc.grouper',
            3 => 'inacbg-service.rest.map',
            4 => 'inacbg-service.rest.inacbg',
            5 => 'inacbg-service.rest.list-procedure',
            6 => 'inacbg-service.rest.grouping',
            7 => 'inacbg-service.rest.hasil-grouping',
            8 => 'inacbg-service.rest.tipe-inacbg',
        ),
    ),
    'zf-rpc' => array(
        'INACBGService\\V1\\Rpc\\Grouper\\Controller' => array(
            'service_name' => 'Grouper',
            'http_methods' => array(
                0 => 'GET',
                1 => 'POST',
            ),
            'route_name' => 'inacbg-service.rpc.grouper',
        ),
    ),
    'zf-content-negotiation' => array(
        'controllers' => array(
            'INACBGService\\V1\\Rpc\\Grouper\\Controller' => 'Json',
            'INACBGService\\V1\\Rest\\Map\\Controller' => 'Json',
            'INACBGService\\V1\\Rest\\Inacbg\\Controller' => 'Json',
            'INACBGService\\V1\\Rest\\ListProcedure\\Controller' => 'Json',
            'INACBGService\\V1\\Rest\\Grouping\\Controller' => 'Json',
            'INACBGService\\V1\\Rest\\HasilGrouping\\Controller' => 'Json',
            'INACBGService\\V1\\Rest\\TipeINACBG\\Controller' => 'Json',
        ),
        'accept_whitelist' => array(
            'INACBGService\\V1\\Rpc\\Grouper\\Controller' => array(
                0 => 'application/vnd.inacbg-service.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'INACBGService\\V1\\Rest\\Map\\Controller' => array(
                0 => 'application/vnd.inacbg-service.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ),
            'INACBGService\\V1\\Rest\\Inacbg\\Controller' => array(
                0 => 'application/vnd.inacbg-service.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ),
            'INACBGService\\V1\\Rest\\ListProcedure\\Controller' => array(
                0 => 'application/vnd.inacbg-service.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ),
            'INACBGService\\V1\\Rest\\Grouping\\Controller' => array(
                0 => 'application/vnd.inacbg-service.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ),
            'INACBGService\\V1\\Rest\\HasilGrouping\\Controller' => array(
                0 => 'application/vnd.inacbg-service.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ),
            'INACBGService\\V1\\Rest\\TipeINACBG\\Controller' => array(
                0 => 'application/vnd.inacbg-service.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ),
        ),
        'content_type_whitelist' => array(
            'INACBGService\\V1\\Rpc\\Grouper\\Controller' => array(
                0 => 'application/vnd.inacbg-service.v1+json',
                1 => 'application/json',
            ),
            'INACBGService\\V1\\Rest\\Map\\Controller' => array(
                0 => 'application/vnd.inacbg-service.v1+json',
                1 => 'application/json',
            ),
            'INACBGService\\V1\\Rest\\Inacbg\\Controller' => array(
                0 => 'application/vnd.inacbg-service.v1+json',
                1 => 'application/json',
            ),
            'INACBGService\\V1\\Rest\\ListProcedure\\Controller' => array(
                0 => 'application/vnd.inacbg-service.v1+json',
                1 => 'application/json',
            ),
            'INACBGService\\V1\\Rest\\Grouping\\Controller' => array(
                0 => 'application/vnd.inacbg-service.v1+json',
                1 => 'application/json',
            ),
            'INACBGService\\V1\\Rest\\HasilGrouping\\Controller' => array(
                0 => 'application/vnd.inacbg-service.v1+json',
                1 => 'application/json',
            ),
            'INACBGService\\V1\\Rest\\TipeINACBG\\Controller' => array(
                0 => 'application/vnd.inacbg-service.v1+json',
                1 => 'application/json',
            ),
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'INACBGService\\V1\\Rest\\Map\\MapResource' => 'INACBGService\\V1\\Rest\\Map\\MapResourceFactory',
            'INACBGService\\V1\\Rest\\Inacbg\\InacbgResource' => 'INACBGService\\V1\\Rest\\Inacbg\\InacbgResourceFactory',
            'INACBGService\\V1\\Rest\\ListProcedure\\ListProcedureResource' => 'INACBGService\\V1\\Rest\\ListProcedure\\ListProcedureResourceFactory',
            'INACBGService\\V1\\Rest\\Grouping\\GroupingResource' => 'INACBGService\\V1\\Rest\\Grouping\\GroupingResourceFactory',
            'INACBGService\\V1\\Rest\\HasilGrouping\\HasilGroupingResource' => 'INACBGService\\V1\\Rest\\HasilGrouping\\HasilGroupingResourceFactory',
            'INACBGService\\V1\\Rest\\TipeINACBG\\TipeINACBGResource' => 'INACBGService\\V1\\Rest\\TipeINACBG\\TipeINACBGResourceFactory',
        ),
    ),
    'zf-rest' => array(
        'INACBGService\\V1\\Rest\\Map\\Controller' => array(
            'listener' => 'INACBGService\\V1\\Rest\\Map\\MapResource',
            'route_name' => 'inacbg-service.rest.map',
            'route_identifier_name' => 'id',
            'collection_name' => 'map',
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
                0 => 'JENIS',
            ),
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => 'INACBGService\\V1\\Rest\\Map\\MapEntity',
            'collection_class' => 'INACBGService\\V1\\Rest\\Map\\MapCollection',
            'service_name' => 'map',
        ),
        'INACBGService\\V1\\Rest\\Inacbg\\Controller' => array(
            'listener' => 'INACBGService\\V1\\Rest\\Inacbg\\InacbgResource',
            'route_name' => 'inacbg-service.rest.inacbg',
            'route_identifier_name' => 'id',
            'collection_name' => 'inacbg',
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
                0 => 'JENIS',
				1 => 'VERSION',
            ),
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => 'INACBGService\\V1\\Rest\\Inacbg\\InacbgEntity',
            'collection_class' => 'INACBGService\\V1\\Rest\\Inacbg\\InacbgCollection',
            'service_name' => 'inacbg',
        ),
        'INACBGService\\V1\\Rest\\ListProcedure\\Controller' => array(
            'listener' => 'INACBGService\\V1\\Rest\\ListProcedure\\ListProcedureResource',
            'route_name' => 'inacbg-service.rest.list-procedure',
            'route_identifier_name' => 'id',
            'collection_name' => 'list_procedure',
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
            'entity_class' => 'INACBGService\\V1\\Rest\\ListProcedure\\ListProcedureEntity',
            'collection_class' => 'INACBGService\\V1\\Rest\\ListProcedure\\ListProcedureCollection',
            'service_name' => 'ListProcedure',
        ),
        'INACBGService\\V1\\Rest\\Grouping\\Controller' => array(
            'listener' => 'INACBGService\\V1\\Rest\\Grouping\\GroupingResource',
            'route_name' => 'inacbg-service.rest.grouping',
            'route_identifier_name' => 'id',
            'collection_name' => 'grouping',
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
                1 => 'DATA',
            ),
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => 'INACBGService\\V1\\Rest\\Grouping\\GroupingEntity',
            'collection_class' => 'INACBGService\\V1\\Rest\\Grouping\\GroupingCollection',
            'service_name' => 'Grouping',
        ),
        'INACBGService\\V1\\Rest\\HasilGrouping\\Controller' => array(
            'listener' => 'INACBGService\\V1\\Rest\\HasilGrouping\\HasilGroupingResource',
            'route_name' => 'inacbg-service.rest.hasil-grouping',
            'route_identifier_name' => 'id',
            'collection_name' => 'hasil_grouping',
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
            ),
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => 'INACBGService\\V1\\Rest\\HasilGrouping\\HasilGroupingEntity',
            'collection_class' => 'INACBGService\\V1\\Rest\\HasilGrouping\\HasilGroupingCollection',
            'service_name' => 'HasilGrouping',
        ),
        'INACBGService\\V1\\Rest\\TipeINACBG\\Controller' => array(
            'listener' => 'INACBGService\\V1\\Rest\\TipeINACBG\\TipeINACBGResource',
            'route_name' => 'inacbg-service.rest.tipe-inacbg',
            'route_identifier_name' => 'id',
            'collection_name' => 'tipe_inacbg',
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
                0 => 'STATUS',
            ),
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => 'INACBGService\\V1\\Rest\\TipeINACBG\\TipeINACBGEntity',
            'collection_class' => 'INACBGService\\V1\\Rest\\TipeINACBG\\TipeINACBGCollection',
            'service_name' => 'TipeINACBG',
        ),
    ),
    'zf-hal' => array(
        'metadata_map' => array(
            'INACBGService\\V1\\Rest\\Map\\MapEntity' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'inacbg-service.rest.map',
                'route_identifier_name' => 'id',
                'hydrator' => 'Zend\\Stdlib\\Hydrator\\ArraySerializable',
            ),
            'INACBGService\\V1\\Rest\\Map\\MapCollection' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'inacbg-service.rest.map',
                'route_identifier_name' => 'id',
                'is_collection' => true,
            ),
            'INACBGService\\V1\\Rest\\Inacbg\\InacbgEntity' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'inacbg-service.rest.inacbg',
                'route_identifier_name' => 'id',
                'hydrator' => 'Zend\\Stdlib\\Hydrator\\ArraySerializable',
            ),
            'INACBGService\\V1\\Rest\\Inacbg\\InacbgCollection' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'inacbg-service.rest.inacbg',
                'route_identifier_name' => 'id',
                'is_collection' => true,
            ),
            'INACBGService\\V1\\Rest\\ListProcedure\\ListProcedureEntity' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'inacbg-service.rest.list-procedure',
                'route_identifier_name' => 'id',
                'hydrator' => 'Zend\\Stdlib\\Hydrator\\ArraySerializable',
            ),
            'INACBGService\\V1\\Rest\\ListProcedure\\ListProcedureCollection' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'inacbg-service.rest.list-procedure',
                'route_identifier_name' => 'id',
                'is_collection' => true,
            ),
            'INACBGService\\V1\\Rest\\Grouping\\GroupingEntity' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'inacbg-service.rest.grouping',
                'route_identifier_name' => 'id',
                'hydrator' => 'Zend\\Stdlib\\Hydrator\\ArraySerializable',
            ),
            'INACBGService\\V1\\Rest\\Grouping\\GroupingCollection' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'inacbg-service.rest.grouping',
                'route_identifier_name' => 'id',
                'is_collection' => true,
            ),
            'INACBGService\\V1\\Rest\\HasilGrouping\\HasilGroupingEntity' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'inacbg-service.rest.hasil-grouping',
                'route_identifier_name' => 'id',
                'hydrator' => 'Zend\\Stdlib\\Hydrator\\ArraySerializable',
            ),
            'INACBGService\\V1\\Rest\\HasilGrouping\\HasilGroupingCollection' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'inacbg-service.rest.hasil-grouping',
                'route_identifier_name' => 'id',
                'is_collection' => true,
            ),
            'INACBGService\\V1\\Rest\\TipeINACBG\\TipeINACBGEntity' => array(
                'entity_identifier_name' => 'ID',
                'route_name' => 'inacbg-service.rest.tipe-inacbg',
                'route_identifier_name' => 'id',
                'hydrator' => 'Zend\\Hydrator\\ArraySerializable',
            ),
            'INACBGService\\V1\\Rest\\TipeINACBG\\TipeINACBGCollection' => array(
                'entity_identifier_name' => 'ID',
                'route_name' => 'inacbg-service.rest.tipe-inacbg',
                'route_identifier_name' => 'id',
                'is_collection' => true,
            ),
        ),
    ),
);
