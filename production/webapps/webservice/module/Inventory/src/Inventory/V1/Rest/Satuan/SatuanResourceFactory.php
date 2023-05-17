<?php
namespace Inventory\V1\Rest\Satuan;

class SatuanResourceFactory
{
    public function __invoke($services)
    {
        return new SatuanResource();
    }
}
