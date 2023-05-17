<?php
namespace MedicalRecord\V1\Rest\TandaVital;

class TandaVitalResourceFactory
{
    public function __invoke($services)
    {
        return new TandaVitalResource();
    }
}
