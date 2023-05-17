<?php
namespace Pembayaran\V1\Rest\TransaksiKasir;

class TransaksiKasirResourceFactory
{
    public function __invoke($services)
    {
        return new TransaksiKasirResource();
    }
}
