<?php
namespace General\V1\Rest\RuanganRadiologi;

class RuanganRadiologiResourceFactory
{
    public function __invoke($services)
    {
        return new RuanganRadiologiResource();
    }
}
