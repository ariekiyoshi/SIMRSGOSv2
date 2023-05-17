<?php
namespace General\V1\Rest\Perawat;

class PerawatResourceFactory
{
    public function __invoke($services)
    {
        return new PerawatResource();
    }
}
