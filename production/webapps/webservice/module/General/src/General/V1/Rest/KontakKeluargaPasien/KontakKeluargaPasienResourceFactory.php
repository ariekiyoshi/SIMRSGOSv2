<?php
namespace General\V1\Rest\KontakKeluargaPasien;

class KontakKeluargaPasienResourceFactory
{
    public function __invoke($services)
    {
        return new KontakKeluargaPasienResource();
    }
}
