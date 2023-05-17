<?php
namespace MedicalRecord\V1\Rest\ICD10;

class ICD10ResourceFactory
{
    public function __invoke($services)
    {
        return new ICD10Resource();
    }
}
