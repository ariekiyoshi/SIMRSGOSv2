<?php
namespace MedicalRecord\V1\Rest\Anamnesis;

class AnamnesisResourceFactory
{
    public function __invoke($services)
    {
		$obj = new AnamnesisResource();
    	$obj->setServiceManager($services);
        return $obj;
    }
}
