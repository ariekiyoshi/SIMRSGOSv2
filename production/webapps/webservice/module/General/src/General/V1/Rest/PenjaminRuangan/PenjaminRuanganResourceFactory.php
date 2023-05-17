<?php
namespace General\V1\Rest\PenjaminRuangan;

class PenjaminRuanganResourceFactory
{
    public function __invoke($services)
    {
        return new PenjaminRuanganResource();
    }
}
