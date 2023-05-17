<?php
namespace General\V1\Rest\KIP;

class KIPResourceFactory
{
    public function __invoke($services)
    {
        return new KIPResource();
    }
}
