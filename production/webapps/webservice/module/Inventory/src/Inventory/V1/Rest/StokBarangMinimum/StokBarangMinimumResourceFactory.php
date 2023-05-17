<?php
namespace Inventory\V1\Rest\StokBarangMinimum;

class StokBarangMinimumResourceFactory
{
    public function __invoke($services)
    {
        return new StokBarangMinimumResource();
    }
}
