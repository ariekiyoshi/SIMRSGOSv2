<?php
namespace Pembayaran\V1\Rest\PembayaranTagihan;

class PembayaranTagihanResourceFactory
{
    public function __invoke($services)
    {
        return new PembayaranTagihanResource();
    }
}
