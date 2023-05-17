<?php
namespace Layanan\V1\Rest\TindakanMedis;

class TindakanMedisResourceFactory
{
    public function __invoke($services)
    {
        return new TindakanMedisResource();
    }
}
