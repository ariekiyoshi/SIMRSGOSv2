<?php
namespace Pendaftaran\V1\Rest\Mutasi;

class MutasiResourceFactory
{
    public function __invoke($services)
    {
        return new MutasiResource();
    }
}
