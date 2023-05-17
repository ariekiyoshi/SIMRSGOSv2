<?php
namespace Pembayaran\V1\Rest\RincianTagihanPaket;

class RincianTagihanPaketResourceFactory
{
    public function __invoke($services)
    {
        return new RincianTagihanPaketResource();
    }
}
