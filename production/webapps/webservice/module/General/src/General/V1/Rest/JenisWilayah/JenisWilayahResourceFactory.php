<?php
namespace General\V1\Rest\JenisWilayah;

class JenisWilayahResourceFactory
{
    public function __invoke($services)
    {
        return new JenisWilayahResource();
    }
}
