<?php
namespace Pendaftaran\V1\Rest\PenanggungJawabPasien;

class PenanggungJawabPasienResourceFactory
{
    public function __invoke($services)
    {
		$obj = new PenanggungJawabPasienResource();
    	$obj->setServiceManager($services);
        return $obj;
    }
}
