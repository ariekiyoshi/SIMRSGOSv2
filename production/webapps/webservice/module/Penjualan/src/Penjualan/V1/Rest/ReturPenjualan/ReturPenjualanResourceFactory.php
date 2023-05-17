<?php
namespace Penjualan\V1\Rest\ReturPenjualan;

class ReturPenjualanResourceFactory
{
    public function __invoke($services)
    {
        return new ReturPenjualanResource();
    }
}
