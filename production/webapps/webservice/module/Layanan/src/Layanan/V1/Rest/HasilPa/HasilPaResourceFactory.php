<?php
namespace Layanan\V1\Rest\HasilPa;

class HasilPaResourceFactory
{
    public function __invoke($services)
    {
        return new HasilPaResource();
    }
}
