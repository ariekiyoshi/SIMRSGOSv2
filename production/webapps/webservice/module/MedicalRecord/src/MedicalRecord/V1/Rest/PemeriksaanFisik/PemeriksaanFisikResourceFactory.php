<?php
namespace MedicalRecord\V1\Rest\PemeriksaanFisik;

class PemeriksaanFisikResourceFactory
{
    public function __invoke($services)
    {
		$obj = new PemeriksaanFisikResource();
    	$obj->setServiceManager($services);
        return $obj;
    }
}
