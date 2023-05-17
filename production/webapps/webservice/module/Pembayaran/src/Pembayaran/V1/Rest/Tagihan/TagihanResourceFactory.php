<?php
namespace Pembayaran\V1\Rest\Tagihan;

class TagihanResourceFactory
{
    public function __invoke($services)
    {
        return new TagihanResource();
    }
}
