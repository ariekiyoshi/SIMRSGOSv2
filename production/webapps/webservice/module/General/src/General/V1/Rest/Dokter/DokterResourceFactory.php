<?php
namespace General\V1\Rest\Dokter;

class DokterResourceFactory
{
    public function __invoke($services)
    {
        return new DokterResource();
    }
}
