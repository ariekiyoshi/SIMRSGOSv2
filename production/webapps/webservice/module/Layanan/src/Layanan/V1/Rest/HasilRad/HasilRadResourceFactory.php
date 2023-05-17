<?php
namespace Layanan\V1\Rest\HasilRad;

class HasilRadResourceFactory
{
    public function __invoke($services)
    {
        return new HasilRadResource();
    }
}
