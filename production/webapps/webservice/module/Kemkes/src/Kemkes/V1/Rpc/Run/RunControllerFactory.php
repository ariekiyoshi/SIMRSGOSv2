<?php
namespace Kemkes\V1\Rpc\Run;

class RunControllerFactory
{
    public function __invoke($controllers)
    {
        return new RunController($controllers);
    }
}
