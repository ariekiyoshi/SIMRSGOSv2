<?php
namespace Aplikasi\db\bridge_log;

use DBService\SystemArrayObject;

class Entity extends SystemArrayObject
{
    protected $fields = array(
        "ID" => 1
        , "URL" => 1
        , "JENIS" => 1
        , "TGL_REQUEST" => 1
        , "REQUEST" => 1
        , "TGL_RESPONSE" => 1
        , "RESPONSE" => 1
        , "ACCESS_FROM_IP" => 1
    );
}
