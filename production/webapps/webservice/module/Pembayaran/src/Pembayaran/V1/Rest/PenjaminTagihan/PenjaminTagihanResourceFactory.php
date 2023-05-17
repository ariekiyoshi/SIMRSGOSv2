<?php
namespace Pembayaran\V1\Rest\PenjaminTagihan;

class PenjaminTagihanResourceFactory
{
    public function __invoke($services)
    {
        return new PenjaminTagihanResource();
    }
}
