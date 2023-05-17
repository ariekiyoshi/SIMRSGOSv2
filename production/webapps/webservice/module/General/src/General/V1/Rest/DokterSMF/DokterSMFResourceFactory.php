<?php
namespace General\V1\Rest\DokterSMF;

class DokterSMFResourceFactory
{
    public function __invoke($services)
    {
        return new DokterSMFResource();
    }
}
