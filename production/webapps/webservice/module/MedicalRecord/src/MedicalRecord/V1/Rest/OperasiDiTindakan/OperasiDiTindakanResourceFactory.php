<?php
namespace MedicalRecord\V1\Rest\OperasiDiTindakan;

class OperasiDiTindakanResourceFactory
{
    public function __invoke($services)
    {
        return new OperasiDiTindakanResource();
    }
}
