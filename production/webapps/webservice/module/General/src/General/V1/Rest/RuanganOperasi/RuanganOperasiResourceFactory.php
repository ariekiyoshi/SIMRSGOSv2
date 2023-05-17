<?php
namespace General\V1\Rest\RuanganOperasi;

class RuanganOperasiResourceFactory
{
    public function __invoke($services)
    {
        return new RuanganOperasiResource();
    }
}
