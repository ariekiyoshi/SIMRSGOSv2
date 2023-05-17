<?php
namespace General\V1\Rest\Wilayah;

class WilayahResourceFactory
{
    public function __invoke($services)
    {
        return new WilayahResource();
    }
}
