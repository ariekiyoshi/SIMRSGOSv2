<?php
namespace General\V1\Rest\JenisLaporanDetil;

class JenisLaporanDetilResourceFactory
{
    public function __invoke($services)
    {
        return new JenisLaporanDetilResource();
    }
}
