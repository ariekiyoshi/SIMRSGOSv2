<?php
namespace Inventory\V1\Rest\TransaksiStokRuangan;

class TransaksiStokRuanganResourceFactory
{
    public function __invoke($services)
    {
        return new TransaksiStokRuanganResource();
    }
}
