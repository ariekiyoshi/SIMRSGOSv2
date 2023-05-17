<?php
namespace MedicalRecord\V1\Rest\RiwayatAlergi;

class RiwayatAlergiResourceFactory
{
    public function __invoke($services)
    {
        return new RiwayatAlergiResource();
    }
}
