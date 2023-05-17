<?php
namespace Kemkes\V2\Rpc\Bor;

class BorControllerFactory
{
    public function __invoke($controllers)
    {
        return new BorController();
    }
}
