<?php
namespace Aplikasi\V1\Rest\PenggunaLog;

class PenggunaLogResourceFactory
{
    public function __invoke($services)
    {
        return new PenggunaLogResource();
    }
}
