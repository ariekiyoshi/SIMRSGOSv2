<?php
namespace General\V1\Rest\JenisReferensi;

class JenisReferensiResourceFactory
{
    public function __invoke($services)
    {
        return new JenisReferensiResource();
    }
}
