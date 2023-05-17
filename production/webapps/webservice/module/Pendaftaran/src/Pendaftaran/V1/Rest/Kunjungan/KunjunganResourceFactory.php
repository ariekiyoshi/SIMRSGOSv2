<?php
namespace Pendaftaran\V1\Rest\Kunjungan;

class KunjunganResourceFactory
{
    public function __invoke($services)
    {
        return new KunjunganResource();
    }
}
