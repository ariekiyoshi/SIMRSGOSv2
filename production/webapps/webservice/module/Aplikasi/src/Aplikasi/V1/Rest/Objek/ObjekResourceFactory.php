<?php
namespace Aplikasi\V1\Rest\Objek;

class ObjekResourceFactory
{
    public function __invoke($services)
    {
        return new ObjekResource();
    }
}
