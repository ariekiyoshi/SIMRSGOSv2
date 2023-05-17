<?php
namespace Pembayaran\V1\Rest\PembatalanTagihan;

class PembatalanTagihanResourceFactory
{
    public function __invoke($services)
    {
        return new PembatalanTagihanResource();
    }
}
