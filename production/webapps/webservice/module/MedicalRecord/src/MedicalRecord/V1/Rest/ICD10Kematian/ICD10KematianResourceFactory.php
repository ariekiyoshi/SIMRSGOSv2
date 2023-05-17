<?php
namespace MedicalRecord\V1\Rest\ICD10Kematian;

class ICD10KematianResourceFactory
{
    public function __invoke($services)
    {
        return new ICD10KematianResource();
    }
}
