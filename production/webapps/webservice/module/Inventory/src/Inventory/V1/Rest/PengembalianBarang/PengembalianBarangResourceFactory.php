<?php
namespace Inventory\V1\Rest\PengembalianBarang;

class PengembalianBarangResourceFactory
{
    public function __invoke($services)
    {
        return new PengembalianBarangResource();
    }
}
