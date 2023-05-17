<?php
namespace Pendaftaran\V1\Rest\PanggilanAntrianRuangan;

class PanggilanAntrianRuanganResourceFactory
{
    public function __invoke($services)
    {
        return new PanggilanAntrianRuanganResource();
    }
}
