<?php
namespace General\V1\Rest\JenisKunjunganRuangan;

class JenisKunjunganRuanganResourceFactory
{
    public function __invoke($services)
    {
        return new JenisKunjunganRuanganResource();
    }
}
