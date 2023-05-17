<?php
namespace Aplikasi\V1\Rest\Instansi;

class InstansiResourceFactory
{
    public function __invoke($services)
    {
        return new InstansiResource();
    }
}
