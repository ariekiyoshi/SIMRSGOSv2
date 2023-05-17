<?php
namespace Pendaftaran\V1\Rest\RujukanKeluar;

class RujukanKeluarResourceFactory
{
    public function __invoke($services)
    {
        return new RujukanKeluarResource();
    }
}
