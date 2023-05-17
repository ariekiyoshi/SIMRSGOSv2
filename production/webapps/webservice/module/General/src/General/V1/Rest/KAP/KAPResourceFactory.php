<?php
namespace General\V1\Rest\KAP;

class KAPResourceFactory
{
    public function __invoke($services)
    {
        return new KAPResource();
    }
}
