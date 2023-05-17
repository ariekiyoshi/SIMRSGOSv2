<?php
namespace General\V1\Rest\KeluargaPasien;

class KeluargaPasienResourceFactory
{
    public function __invoke($services)
    {
        return new KeluargaPasienResource();
    }
}
