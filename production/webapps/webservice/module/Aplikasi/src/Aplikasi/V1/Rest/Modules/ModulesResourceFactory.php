<?php
namespace Aplikasi\V1\Rest\Modules;

class ModulesResourceFactory
{
    public function __invoke($services)
    {
        return new ModulesResource();
    }
}
