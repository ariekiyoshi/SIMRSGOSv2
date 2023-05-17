<?php
namespace General\V1\Rest\TenagaMedis;

class TenagaMedisResourceFactory
{
    public function __invoke($services)
    {
        return new TenagaMedisResource();
    }
}
