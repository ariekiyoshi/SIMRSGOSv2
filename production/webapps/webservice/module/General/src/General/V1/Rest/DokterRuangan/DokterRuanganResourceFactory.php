<?php
namespace General\V1\Rest\DokterRuangan;

class DokterRuanganResourceFactory
{
    public function __invoke($services)
    {
        return new DokterRuanganResource();
    }
}
