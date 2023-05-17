<?php
namespace Pembayaran\V1\Rest\EDC;

class EDCResourceFactory
{
    public function __invoke($services)
    {
        return new EDCResource();
    }
}
