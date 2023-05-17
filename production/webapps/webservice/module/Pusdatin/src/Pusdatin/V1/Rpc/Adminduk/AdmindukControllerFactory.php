<?php
namespace Pusdatin\V1\Rpc\Adminduk;

class AdmindukControllerFactory
{
    public function __invoke($controllers)
    {
        return new AdmindukController($controllers);
    }
}
