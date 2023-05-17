<?php
namespace Pendaftaran\V1\Rest\KIPPenanggungJawab;

class KIPPenanggungJawabResourceFactory
{
    public function __invoke($services)
    {
        return new KIPPenanggungJawabResource();
    }
}
