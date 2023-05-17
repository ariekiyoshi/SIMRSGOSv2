<?php
namespace Pendaftaran\V1\Rest\Pendaftaran;

class PendaftaranResourceFactory
{
    public function __invoke($services)
    {
        return new PendaftaranResource();
    }
}
