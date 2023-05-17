<?php
namespace Layanan\V1\Rest\O2;

class O2ResourceFactory
{
    public function __invoke($services)
    {
        return new O2Resource();
    }
}
