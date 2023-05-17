<?php
namespace Cetakan\V1\Rest\KartuPasien;

class KartuPasienResourceFactory
{
    public function __invoke($services)
    {
        return new KartuPasienResource();
    }
}
