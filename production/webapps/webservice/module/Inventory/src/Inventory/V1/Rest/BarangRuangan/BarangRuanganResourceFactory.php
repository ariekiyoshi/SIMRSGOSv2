<?php
namespace Inventory\V1\Rest\BarangRuangan;

class BarangRuanganResourceFactory
{
    public function __invoke($services)
    {
        return new BarangRuanganResource();
    }
}
