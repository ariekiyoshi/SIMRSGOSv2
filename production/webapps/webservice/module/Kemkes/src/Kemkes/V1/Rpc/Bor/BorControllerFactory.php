<?php
namespace Kemkes\V1\Rpc\Bor;

class BorControllerFactory
{
    public function __invoke($controllers)
    {
        return new BorController();
    }
}
