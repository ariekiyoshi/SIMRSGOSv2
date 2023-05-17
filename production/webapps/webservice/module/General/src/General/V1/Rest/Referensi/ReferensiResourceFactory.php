<?php
namespace General\V1\Rest\Referensi;

class ReferensiResourceFactory
{
    public function __invoke($services)
    {
        return new ReferensiResource();
    }
}
