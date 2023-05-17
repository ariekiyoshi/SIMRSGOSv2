<?php
namespace Aplikasi\V1\Rest\Pengguna;

class PenggunaResourceFactory
{
    public function __invoke($services)
    {
        return new PenggunaResource();
    }
}
