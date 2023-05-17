<?php
namespace Pembayaran\V1\Rest\PiutangPasien;

class PiutangPasienResourceFactory
{
    public function __invoke($services)
    {
        return new PiutangPasienResource();
    }
}
