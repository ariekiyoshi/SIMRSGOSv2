<?php
namespace Cetakan\V1\Rest\KwitansiPembayaran;

class KwitansiPembayaranResourceFactory
{
    public function __invoke($services)
    {
        return new KwitansiPembayaranResource();
    }
}
