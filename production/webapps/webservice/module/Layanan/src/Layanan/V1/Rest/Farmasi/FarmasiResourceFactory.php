<?php
namespace Layanan\V1\Rest\Farmasi;

class FarmasiResourceFactory
{
    public function __invoke($services)
    {
        return new FarmasiResource();
    }
}
