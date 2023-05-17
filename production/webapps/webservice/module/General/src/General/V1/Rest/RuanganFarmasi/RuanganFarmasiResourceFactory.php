<?php
namespace General\V1\Rest\RuanganFarmasi;

class RuanganFarmasiResourceFactory
{
    public function __invoke($services)
    {
        return new RuanganFarmasiResource();
    }
}
