<?php
namespace Inventory\V1\Rest\PengembalianBarangDetil;

class PengembalianBarangDetilResourceFactory
{
    public function __invoke($services)
    {
        return new PengembalianBarangDetilResource();
    }
}
