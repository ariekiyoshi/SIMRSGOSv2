<?php
namespace MedicalRecord\V1\Rest\RiwayatPerjalananPenyakit;

class RiwayatPerjalananPenyakitResourceFactory
{
    public function __invoke($services)
    {
        $obj = new RiwayatPerjalananPenyakitResource();
    	$obj->setServiceManager($services);
        return $obj;
    }
}
