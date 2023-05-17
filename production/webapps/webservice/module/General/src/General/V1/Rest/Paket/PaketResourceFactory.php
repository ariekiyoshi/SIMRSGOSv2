<?php
namespace General\V1\Rest\Paket;

class PaketResourceFactory
{
    public function __invoke($services)
    {
        return new PaketResource();
    }
}
