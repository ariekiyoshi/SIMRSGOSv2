<?php
namespace Inventory\V1\Rest\Barang;

class BarangResourceFactory
{
    public function __invoke($services)
    {
        return new BarangResource();
    }
}
