<?php
namespace Inventory\V1\Rest\PengirimanDetil;

class PengirimanDetilResourceFactory
{
    public function __invoke($services)
    {
        return new PengirimanDetilResource();
    }
}
