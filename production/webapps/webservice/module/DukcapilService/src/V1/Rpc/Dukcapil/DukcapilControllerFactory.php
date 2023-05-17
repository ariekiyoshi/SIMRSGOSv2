<?php
namespace DukcapilService\V1\Rpc\Dukcapil;

class DukcapilControllerFactory
{
    public function __invoke($controllers)
    {
        return new DukcapilController($controllers);
    }
}
