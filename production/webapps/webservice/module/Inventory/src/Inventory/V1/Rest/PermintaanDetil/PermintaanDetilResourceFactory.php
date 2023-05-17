<?php
namespace Inventory\V1\Rest\PermintaanDetil;

class PermintaanDetilResourceFactory
{
    public function __invoke($services)
    {
        return new PermintaanDetilResource();
    }
}
