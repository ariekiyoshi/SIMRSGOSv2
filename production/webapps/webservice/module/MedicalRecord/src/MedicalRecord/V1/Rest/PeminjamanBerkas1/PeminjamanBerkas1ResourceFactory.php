<?php
namespace MedicalRecord\V1\Rest\PeminjamanBerkas1;

class PeminjamanBerkas1ResourceFactory
{
    public function __invoke($services)
    {
        return new PeminjamanBerkas1Resource();
    }
}
