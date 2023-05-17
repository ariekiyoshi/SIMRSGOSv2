<?php
namespace MedicalRecord\V1\Rest\PeminjamanBerkas;

class PeminjamanBerkasResourceFactory
{
    public function __invoke($services)
    {
        return new PeminjamanBerkasResource();
    }
}
