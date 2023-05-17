<?php
namespace General\V1\Rest\Tindakan;

class TindakanResourceFactory
{
    public function __invoke($services)
    {
        return new TindakanResource();
    }
}
