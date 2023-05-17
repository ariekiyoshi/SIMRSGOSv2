<?php
namespace Inventory\V1\Rest\PenerimaanBarang;

class PenerimaanBarangResourceFactory
{
    public function __invoke($services)
    {
        return new PenerimaanBarangResource();
    }
}
