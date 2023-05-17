<?php
namespace Layanan\V1\Rest\PasienMeninggal;

class PasienMeninggalResourceFactory
{
    public function __invoke($services)
    {
        return new PasienMeninggalResource();
    }
}
