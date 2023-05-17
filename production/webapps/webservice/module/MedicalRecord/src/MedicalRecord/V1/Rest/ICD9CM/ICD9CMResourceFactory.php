<?php
namespace MedicalRecord\V1\Rest\ICD9CM;

class ICD9CMResourceFactory
{
    public function __invoke($services)
    {
        return new ICD9CMResource();
    }
}
