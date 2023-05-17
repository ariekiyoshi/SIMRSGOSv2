<?php
namespace MedicalRecord\V1\Rest\Diagnosa;

class DiagnosaResourceFactory
{
    public function __invoke($services)
    {
        return new DiagnosaResource();
    }
}
