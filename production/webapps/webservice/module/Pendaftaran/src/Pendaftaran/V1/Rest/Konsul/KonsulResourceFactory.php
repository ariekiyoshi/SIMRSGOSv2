<?php
namespace Pendaftaran\V1\Rest\Konsul;

class KonsulResourceFactory
{
    public function __invoke($services)
    {
        return new KonsulResource();
    }
}
