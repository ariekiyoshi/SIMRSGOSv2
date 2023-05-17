<?php
namespace Pembayaran\V1\Rest\Diskon;

class DiskonResourceFactory
{
    public function __invoke($services)
    {
        return new DiskonResource();
    }
}
