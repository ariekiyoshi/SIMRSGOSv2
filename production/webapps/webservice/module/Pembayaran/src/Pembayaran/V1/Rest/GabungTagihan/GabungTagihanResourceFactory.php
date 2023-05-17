<?php
namespace Pembayaran\V1\Rest\GabungTagihan;

class GabungTagihanResourceFactory
{
    public function __invoke($services)
    {
        return new GabungTagihanResource();
    }
}
