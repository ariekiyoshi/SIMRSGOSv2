<?php
namespace Aplikasi\V1\Rpc\Authentication;

class AuthenticationControllerFactory
{
    public function __invoke($controllers)
    {
        return new AuthenticationController();
    }
}
