<?php
namespace General\V1\Rest\Administrasi;

class AdministrasiResourceFactory
{
    public function __invoke($services)
    {
        return new AdministrasiResource();
    }
}
