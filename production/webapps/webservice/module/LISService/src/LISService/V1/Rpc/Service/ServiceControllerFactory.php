<?php
namespace LISService\V1\Rpc\Service;

class ServiceControllerFactory
{
    public function __invoke($controllers)
    {
        return new ServiceController();
    }
}
