<?php
namespace Generator\V1\Rpc\Signature;

class SignatureControllerFactory
{
    public function __invoke($controllers)
    {
        return new SignatureController();
    }
}
