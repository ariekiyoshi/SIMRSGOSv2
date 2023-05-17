<?php
namespace Inventory\V1\Rest\Pengiriman;

class PengirimanResourceFactory
{
    public function __invoke($services)
    {
        return new PengirimanResource();
    }
}
