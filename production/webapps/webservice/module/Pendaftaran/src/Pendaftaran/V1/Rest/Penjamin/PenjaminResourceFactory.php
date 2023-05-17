<?php
namespace Pendaftaran\V1\Rest\Penjamin;

class PenjaminResourceFactory
{
    public function __invoke($services)
    {
        return new PenjaminResource();
    }
}
