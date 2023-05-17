<?php
namespace Pendaftaran\V1\Rest\SuratRujukanPasien;

class SuratRujukanPasienResourceFactory
{
    public function __invoke($services)
    {
        return new SuratRujukanPasienResource();
    }
}
