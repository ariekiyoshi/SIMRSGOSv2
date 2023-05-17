<?php
namespace Pembayaran\V1\Rest\TagihanPendaftaran;

class TagihanPendaftaranResourceFactory
{
    public function __invoke($services)
    {
        return new TagihanPendaftaranResource();
    }
}
