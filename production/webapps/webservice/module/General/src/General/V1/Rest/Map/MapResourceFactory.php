<?php
namespace General\V1\Rest\Map;

class MapResourceFactory
{
    public function __invoke($services)
    {
        return new MapResource();
    }
}
