<?php
namespace Pembayaran\V1\Rest\DiskonDokter;

class DiskonDokterResourceFactory
{
    public function __invoke($services)
    {
        return new DiskonDokterResource();
    }
}
