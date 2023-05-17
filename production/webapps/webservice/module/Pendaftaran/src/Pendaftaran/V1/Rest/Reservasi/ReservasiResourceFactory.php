<?php
namespace Pendaftaran\V1\Rest\Reservasi;

class ReservasiResourceFactory
{
    public function __invoke($services)
    {
        return new ReservasiResource();
    }
}
