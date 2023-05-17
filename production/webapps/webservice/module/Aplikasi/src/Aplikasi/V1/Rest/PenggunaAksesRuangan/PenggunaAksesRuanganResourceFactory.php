<?php
namespace Aplikasi\V1\Rest\PenggunaAksesRuangan;

class PenggunaAksesRuanganResourceFactory
{
    public function __invoke($services)
    {
        return new PenggunaAksesRuanganResource();
    }
}
