<?php
namespace Aplikasi\V1\Rest\PenggunaAksesLog;

class PenggunaAksesLogResourceFactory
{
    public function __invoke($services)
    {
		$obj = new PenggunaAksesLogResource();
    	$obj->setServiceManager($services);
        return $obj;
    }
}
