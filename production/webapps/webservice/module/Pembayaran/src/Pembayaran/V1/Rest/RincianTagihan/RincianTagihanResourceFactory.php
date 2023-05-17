<?php
namespace Pembayaran\V1\Rest\RincianTagihan;

class RincianTagihanResourceFactory
{
    public function __invoke($services)
    {
        return new RincianTagihanResource();
    }
}
