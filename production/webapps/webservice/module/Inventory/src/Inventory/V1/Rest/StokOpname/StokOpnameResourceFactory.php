<?php
namespace Inventory\V1\Rest\StokOpname;

class StokOpnameResourceFactory
{
    public function __invoke($services)
    {
        return new StokOpnameResource();
    }
}
