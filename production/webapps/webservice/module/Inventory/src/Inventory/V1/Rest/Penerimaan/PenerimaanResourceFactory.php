<?php
namespace Inventory\V1\Rest\Penerimaan;

class PenerimaanResourceFactory
{
    public function __invoke($services)
    {
        return new PenerimaanResource();
    }
}
