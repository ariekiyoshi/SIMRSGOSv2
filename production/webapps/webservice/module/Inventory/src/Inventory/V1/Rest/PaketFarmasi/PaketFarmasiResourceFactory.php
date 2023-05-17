<?php
namespace Inventory\V1\Rest\PaketFarmasi;

class PaketFarmasiResourceFactory
{
    public function __invoke($services)
    {
        return new PaketFarmasiResource();
    }
}
