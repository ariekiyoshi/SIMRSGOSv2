<?php
namespace Kemkes\V2\Rpc\Run;

class RunControllerFactory
{
    public function __invoke($controllers)
    {
        return new RunController($controllers);
    }
}
