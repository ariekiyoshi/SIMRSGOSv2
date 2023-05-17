<?php
namespace Pegawai\V1\Rest\KontakPegawai;

class KontakPegawaiResourceFactory
{
    public function __invoke($services)
    {
        return new KontakPegawaiResource();
    }
}
