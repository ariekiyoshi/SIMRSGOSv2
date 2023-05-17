<?php
namespace Pendaftaran\V1\Rest\JawabanKonsul;

class JawabanKonsulResourceFactory
{
    public function __invoke($services)
    {
        return new JawabanKonsulResource();
    }
}
