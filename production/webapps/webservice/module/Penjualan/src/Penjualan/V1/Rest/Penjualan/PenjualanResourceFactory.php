<?php
namespace Penjualan\V1\Rest\Penjualan;

class PenjualanResourceFactory
{
    public function __invoke($services)
    {
        return new PenjualanResource();
    }
}
