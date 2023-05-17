<?php
namespace Inventory\V1\Rest\PenerimaanBarangDetil;

class PenerimaanBarangDetilResourceFactory
{
    public function __invoke($services)
    {
        return new PenerimaanBarangDetilResource();
    }
}
