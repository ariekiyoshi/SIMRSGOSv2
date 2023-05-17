<?php
namespace Pendaftaran\V1\Rest\AntrianRuangan;

class AntrianRuanganResourceFactory
{
    public function __invoke($services)
    {
        return new AntrianRuanganResource();
    }
}
