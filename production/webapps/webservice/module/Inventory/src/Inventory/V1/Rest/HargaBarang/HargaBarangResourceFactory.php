<?php
namespace Inventory\V1\Rest\HargaBarang;

class HargaBarangResourceFactory
{
    public function __invoke($services)
    {
        return new HargaBarangResource();
    }
}
