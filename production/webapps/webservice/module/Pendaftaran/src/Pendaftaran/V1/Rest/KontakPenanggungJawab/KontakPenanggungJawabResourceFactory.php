<?php
namespace Pendaftaran\V1\Rest\KontakPenanggungJawab;

class KontakPenanggungJawabResourceFactory
{
    public function __invoke($services)
    {
        return new KontakPenanggungJawabResource();
    }
}
