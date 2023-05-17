<?php
namespace Penjualan\V1\Rest\PenjualanDetil;

class PenjualanDetilResourceFactory
{
    public function __invoke($services)
    {
        return new PenjualanDetilResource();
    }
}
