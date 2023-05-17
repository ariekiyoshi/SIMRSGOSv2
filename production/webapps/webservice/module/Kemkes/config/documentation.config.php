<?php
return array(
    'Kemkes\\V1\\Rpc\\Kunjungan\\Controller' => array(
        'description' => 'Web Service Pengiriman Data Kunjungan',
        'GET' => array(
            'description' => '',
            'response' => 'Response Format XML',
        ),
    ),
    'Kemkes\\V1\\Rpc\\Diagnosa\\Controller' => array(
        'GET' => array(
            'response' => 'Response Format XML',
            'description' => '',
        ),
        'description' => 'Web Service Pengiriman Data 10 Diagnosa Terbesar',
    ),
    'Kemkes\\V1\\Rpc\\Bor\\Controller' => array(
        'description' => 'Web Service Pengiriman Data Indikator Pelayanan',
        'GET' => array(
            'response' => 'Response Format XML',
        ),
    ),
    'Kemkes\\V1\\Rest\\BedMonitor\\Controller' => array(
        'collection' => array(
            'description' => 'Response Format XML',
            'GET' => array(
                'response' => 'Response Format XML',
            ),
        ),
        'description' => 'Web Service Pengiriman Data Keadaan Tempat Tidur (Bed Monitor)',
        'entity' => array(
            'GET' => array(
                'response' => 'Response Format XML',
            ),
        ),
    ),
);
