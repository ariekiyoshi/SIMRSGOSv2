<?php
namespace General\V1\Rest\Pegawai;

class PegawaiResourceFactory
{
    public function __invoke($services)
    {
        return new PegawaiResource();
    }
}
