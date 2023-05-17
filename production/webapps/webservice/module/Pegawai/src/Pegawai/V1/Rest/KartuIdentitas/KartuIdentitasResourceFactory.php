<?php
namespace Pegawai\V1\Rest\KartuIdentitas;

class KartuIdentitasResourceFactory
{
    public function __invoke($services)
    {
        return new KartuIdentitasResource();
    }
}
