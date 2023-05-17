<?php
namespace General\V1\Rest\Ruangan;

class RuanganResourceFactory
{
    public function __invoke($services)
    {
        return new RuanganResource();
    }
}
