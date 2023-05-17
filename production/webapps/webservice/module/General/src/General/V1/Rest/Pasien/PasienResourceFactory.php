<?php
namespace General\V1\Rest\Pasien;

class PasienResourceFactory
{
    public function __invoke($services)
    {
		return new PasienResource();
    }
}
