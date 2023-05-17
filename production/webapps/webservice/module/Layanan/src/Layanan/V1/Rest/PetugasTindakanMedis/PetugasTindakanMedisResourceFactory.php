<?php
namespace Layanan\V1\Rest\PetugasTindakanMedis;

class PetugasTindakanMedisResourceFactory
{
    public function __invoke($services)
    {
        return new PetugasTindakanMedisResource();
    }
}
