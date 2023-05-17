<?php
namespace Inventory\V1\Rest\PembatalanPenerimaanBarang;

class PembatalanPenerimaanBarangResourceFactory
{
    public function __invoke($services)
    {
        return new PembatalanPenerimaanBarangResource();
    }
}
