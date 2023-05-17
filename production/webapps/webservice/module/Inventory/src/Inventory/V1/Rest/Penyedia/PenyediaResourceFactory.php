<?php
namespace Inventory\V1\Rest\Penyedia;

class PenyediaResourceFactory
{
    public function __invoke($services)
    {
        return new PenyediaResource();
    }
}
