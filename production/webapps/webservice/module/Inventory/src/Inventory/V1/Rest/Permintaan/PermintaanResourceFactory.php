<?php
namespace Inventory\V1\Rest\Permintaan;

class PermintaanResourceFactory
{
    public function __invoke($services)
    {
        return new PermintaanResource();
    }
}
