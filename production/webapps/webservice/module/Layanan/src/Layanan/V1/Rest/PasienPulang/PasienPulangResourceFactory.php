<?php
namespace Layanan\V1\Rest\PasienPulang;

class PasienPulangResourceFactory
{
    public function __invoke($services)
    {
        return new PasienPulangResource();
    }
}
