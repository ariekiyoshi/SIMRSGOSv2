<?php
namespace Inventory\V1\Rest\Kategori;

class KategoriResourceFactory
{
    public function __invoke($services)
    {
        return new KategoriResource();
    }
}
