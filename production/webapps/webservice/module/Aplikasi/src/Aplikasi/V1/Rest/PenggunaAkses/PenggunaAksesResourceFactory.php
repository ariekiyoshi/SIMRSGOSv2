<?php
namespace Aplikasi\V1\Rest\PenggunaAkses;

class PenggunaAksesResourceFactory
{
    public function __invoke($services)
    {
        return new PenggunaAksesResource();
    }
}
