<?php
namespace Layanan\V1\Rest\ReturFarmasi;

class ReturFarmasiResourceFactory
{
    public function __invoke($services)
    {
        return new ReturFarmasiResource();
    }
}
