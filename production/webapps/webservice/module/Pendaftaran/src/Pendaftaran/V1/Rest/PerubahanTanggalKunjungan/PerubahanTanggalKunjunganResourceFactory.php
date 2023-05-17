<?php
namespace Pendaftaran\V1\Rest\PerubahanTanggalKunjungan;

class PerubahanTanggalKunjunganResourceFactory
{
    public function __invoke($services)
    {
        return new PerubahanTanggalKunjunganResource();
    }
}
