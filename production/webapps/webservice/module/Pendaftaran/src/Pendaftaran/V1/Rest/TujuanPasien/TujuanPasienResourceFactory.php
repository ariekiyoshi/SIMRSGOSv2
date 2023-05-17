<?php
namespace Pendaftaran\V1\Rest\TujuanPasien;

class TujuanPasienResourceFactory
{
    public function __invoke($services)
    {
        return new TujuanPasienResource();
    }
}
