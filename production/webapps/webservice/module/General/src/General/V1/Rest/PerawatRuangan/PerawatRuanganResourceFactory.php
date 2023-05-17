<?php
namespace General\V1\Rest\PerawatRuangan;

class PerawatRuanganResourceFactory
{
    public function __invoke($services)
    {
        return new PerawatRuanganResource();
    }
}
