<?php
return array(
    'controllers' => array(
        'factories' => array(
            'ReportService\\V1\\Rpc\\Report\\Controller' => 'ReportService\\V1\\Rpc\\Report\\ReportControllerFactory',
        ),
    ),
    'router' => array(
        'routes' => array(
            'report-service.rpc.report' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/report',
                    'defaults' => array(
                        'controller' => 'ReportService\\V1\\Rpc\\Report\\Controller',
                        'action' => 'report',
                    ),
                ),
            ),
        ),
    ),
    'zf-versioning' => array(
        'uri' => array(
            0 => 'report-service.rpc.report',
        ),
    ),
    'zf-rpc' => array(
        'ReportService\\V1\\Rpc\\Report\\Controller' => array(
            'service_name' => 'Report',
            'http_methods' => array(
                0 => 'GET',
            ),
            'route_name' => 'report-service.rpc.report',
        ),
    ),
    'zf-content-negotiation' => array(
        'controllers' => array(
            'ReportService\\V1\\Rpc\\Report\\Controller' => 'Json',
        ),
        'accept_whitelist' => array(
            'ReportService\\V1\\Rpc\\Report\\Controller' => array(
                0 => 'application/vnd.report-service.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
                3 => 'text/html',
            ),
        ),
        'content_type_whitelist' => array(
            'ReportService\\V1\\Rpc\\Report\\Controller' => array(
                0 => 'application/vnd.report-service.v1+json',
                1 => 'application/json',
                2 => 'text/html',
            ),
        ),
    ),
);
