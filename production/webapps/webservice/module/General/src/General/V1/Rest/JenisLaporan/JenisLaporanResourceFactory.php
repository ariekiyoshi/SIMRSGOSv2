<?php
namespace General\V1\Rest\JenisLaporan;

class JenisLaporanResourceFactory
{
    public function __invoke($services)
    {
        return new JenisLaporanResource();
    }
}
