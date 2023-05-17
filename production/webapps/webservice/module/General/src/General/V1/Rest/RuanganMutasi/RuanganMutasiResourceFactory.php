<?php
namespace General\V1\Rest\RuanganMutasi;

class RuanganMutasiResourceFactory
{
    public function __invoke($services)
    {
        return new RuanganMutasiResource();
    }
}
