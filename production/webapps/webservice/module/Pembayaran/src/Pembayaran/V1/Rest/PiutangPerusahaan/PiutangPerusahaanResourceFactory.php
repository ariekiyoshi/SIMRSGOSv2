<?php
namespace Pembayaran\V1\Rest\PiutangPerusahaan;

class PiutangPerusahaanResourceFactory
{
    public function __invoke($services)
    {
        return new PiutangPerusahaanResource();
    }
}
