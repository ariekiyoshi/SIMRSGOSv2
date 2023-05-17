<?php
namespace Pembayaran\V1\Rest\Kasir;

class KasirResourceFactory
{
    public function __invoke($services)
    {
        return new KasirResource();
    }
}
