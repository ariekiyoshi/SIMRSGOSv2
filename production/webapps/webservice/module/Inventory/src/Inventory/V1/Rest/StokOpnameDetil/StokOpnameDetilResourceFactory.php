<?php
namespace Inventory\V1\Rest\StokOpnameDetil;

class StokOpnameDetilResourceFactory
{
    public function __invoke($services)
    {
        return new StokOpnameDetilResource();
    }
}
