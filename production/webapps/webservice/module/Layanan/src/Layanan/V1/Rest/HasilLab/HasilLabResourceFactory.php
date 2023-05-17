<?php
namespace Layanan\V1\Rest\HasilLab;

class HasilLabResourceFactory
{
    public function __invoke($services)
    {
        return new HasilLabResource();
    }
}
