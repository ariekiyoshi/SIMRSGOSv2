<?php
namespace Layanan\V1\Rest\JasaTuslahFarmasi;

class JasaTuslahFarmasiResourceFactory
{
    public function __invoke($services)
    {
        return new JasaTuslahFarmasiResource();
    }
}
