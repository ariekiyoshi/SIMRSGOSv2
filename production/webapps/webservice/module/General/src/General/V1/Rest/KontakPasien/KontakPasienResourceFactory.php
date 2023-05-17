<?php
namespace General\V1\Rest\KontakPasien;

class KontakPasienResourceFactory
{
    public function __invoke($services)
    {
        return new KontakPasienResource();
    }
}
