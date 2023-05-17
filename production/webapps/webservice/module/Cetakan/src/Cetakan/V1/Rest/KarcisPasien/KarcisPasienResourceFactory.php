<?php
namespace Cetakan\V1\Rest\KarcisPasien;

class KarcisPasienResourceFactory
{
    public function __invoke($services)
    {
        return new KarcisPasienResource();
    }
}
