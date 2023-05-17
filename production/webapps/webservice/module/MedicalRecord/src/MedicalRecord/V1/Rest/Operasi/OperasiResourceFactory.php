<?php
namespace MedicalRecord\V1\Rest\Operasi;

class OperasiResourceFactory
{
    public function __invoke($services)
    {
        return new OperasiResource();
    }
}
